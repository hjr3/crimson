My personal component framework.  Used for proof of concepts, discussion and my personal projects.  Each component is seperate.  This is to provide loose coupling.  However, some of the components are dependent on Zend Framework.  Every component has the same directory structure so they can be used in any combination.  
I try my best to make sure every component is bug free by providing unit tests with every component.  However, I provide no guarantee that the component will work in your environment.

[![Build Status](https://secure.travis-ci.org/hradtke/crimson.png?branch=master)](http://travis-ci.org/hradtke/crimson)


# Crimson\ExceptionHandler: A syslog friendly exception handler

The is a drop-in exception handler designed to
provide exception stack traces in a syslog friend format.

The Crimson\ExceptionHandler does not force or check that PHP is logging to
syslog.  It expects the PHP ini "error_log" setting to be "syslog" before it 
catches an exception.  If the value is set to some other file, it will log to
that file instead.

# Crimson\Json: An OO implementation of serializing objects into JSON

The Crimson\Json class is an object orienteded implementation for json_encode and json_decode.  The added bonus is that Crimson\Json honors the Crimson\Encodable interface.  This interface works very similiar to the Serializable interface in PHP core: Crimson\Json will call the respective encode/decode method of a class that implements Crimson\Encodable instead of calling the core json_encode/json_decode functions.

Example:

    class Foo implements Crimson\Encodable
    {
        protected $_data = array('hidden' => 'data');
    
        public function encode()
        {
            return json_encode($this->_data);
        }
    
        public function json_decode($value)
        {
            $this->_data = (array) json_decode($value);
        }
    }

Crimson\Url: A set of URL utilities
=============================================================

The Crimson\Url class provides a set of factory methods that take advantage of PHP5.3's new anonymous function and closure features.  This class is implemented in a way that is indepdent of the view.  This allows the code to be accessible to the service layer without compromising the functionality available to the view.
