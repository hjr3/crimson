<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Crimson_ExceptionHandler
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class Crimson_ExceptionHandler
{
    /**
     * Register this class as the exception handler
     */
    public function __construct()
    {
        set_exception_handler(array($this, 'handle'));
    }

    /**
     * Handle an uncaught exception in a syslog friendly format.
     *
     * Syslog commonly limits output to 1024 characters.  This causes the trace
     * to be cut off before showing where the exception occurred.  This handler
     * logs each line of the trace as a seperate entry in syslog using a format
     * very similar to xdebug.
     * 
     * @param Exception $e Uncaught exception object.
     */
    public function handle(Exception $e)
    {
        $m = "{$e->getMessage()} Stack Trace:";
        error_log($m);

        $trace = $e->getTrace();
        foreach ($trace as $k => $l) {
            // these keys do not always exist
            $file = isset($l['file']) ? $l['file'] : '?';
            $line = isset($l['line']) ? $l['line'] : '?';

            $m = "PHP {$k}. {$l['function']} {$file}:{$line}";
            error_log($m);
        }
    }
}
