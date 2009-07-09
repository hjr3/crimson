<?php
/**
 * Controller plugin to secure pages from users who have not logged in.
 *
 * If the user is not allowed to access the contrller/action pair, the
 * request is canceled and the user is directed to the login page.
 *
 * @uses Zend_Controller_Plugin_Abstract
 * @package Crimson 
 * @copyright 2009 Herman Radtke
 * @author Herman Radtke 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
namespace crimson\controller\plugin\Auth;

class Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * The controller to rouite to if an unauthorized user is trying to access
     * a protected route.
     *
     * This controller is implicitly whitelisted.
     * 
     * @var string
     * @access private
     */
    private $_loginController;

    /**
     * The action to route to if an unauthorized user is trying to access
     * a protected route.
     *
     * This action is implicitly whitelisted.
     * 
     * @var string
     * @access private
     */
    private $_loginAction;

    public function __construct()
    {
        $this->setLoginController('index');
        $this->setLoginAction('login');
    }

    /**
     * Restrict access to protected controller/action pairs.
     * 
     * @param Zend_Controller_Request_Abstract $request 
     * @access public
     * @return void
     */
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());

        if (isWhiteListed($controller, $action)) {
            return;
        }

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            return;
        }

        $request->setControllerName($this->getLoginController());
        $request->setActionName($this->getLoginAction());
        $request->setDispatched(true);

        $this->_response->setHttpResponseCode(401);
        $this->_response->clearBody();
    }

    /**
     * Determine if a controller/action pair is whitelisted.
     * 
     * @param string $controller The controller name to check.
     * @param string $action The action name to check.
     * @access public
     * @return boolean true if whitelisted, else false
     */
    public function isWhitelisted($controller, $action)
    {
        if ($controller == $this->getLoginController() &&
            $action == $this->getLoginAction()) {
                return true;
        }

        return false;
    }

    /**
     * Returns the no-authorization controller name.
     * 
     * @access public
     * @return string The controller name.
     */
    public function getLoginController()
    {
        return $this->_loginController;
    }

    /**
     * Sets the no-authorization controller name that will be called if
     * unauthorized access is detected.
     * 
     * @param string $controller The controller name.
     * @access public
     * @return void
     */
    public function setLoginController($controller)
    {
        $this->_loginController = $controller;
    }

    /**
     * Returns the no-authorization action name.
     * 
     * @access public
     * @return string The action name.
     */
    public function getLoginAction()
    {
        return $this->_loginAction;
    }

    /**
     * Sets the login action name that will be called by the login controller 
     * if the user is not logged in.
     * 
     * @param string $action The action name.
     * @access public
     * @return void
     */
    public function setLoginAction($action)
    {
        $this->_loginAction = $action;
    }
}
