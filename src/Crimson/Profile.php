<?php
/**
 * Crimson - component library
 *
 * @category Crimson
 * @package Profile
 * @copyright 2010 Herman J. Radtke III
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Crimson;

class Profile
{
    /**
     * Object used to storage results of profiling
     *
     * @var ProfileStorage
     */
    protected $storage;

    /**
     * Value of the timer when it was first started
     *
     * @var float
     */
    protected $time_start;

    /**
     * Store an instance of ProfileStorage
     *
     * @param ProfileStorage $storage
     */
    public function setStorage(profile\Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Start a timer.
     */
    public function timerStart()
    {
        $this->time_start = microtime(true);
    }

    /**
     * Stop a timer and return result.
     *
     * Don't reset @link($time_start} so incremental times can be taken.
     *
     * @return float The sec.msec time between start and stop
     */
    public function timerStop()
    {
        $end = microtime(true);
        $total = $end - $this->time_start;

        if ($this->storage) {
            $this->storage->saveTimer($data);
        }

        return $total;
    }

    /**
     * Start profiling the code
     */
    public function profileStart()
    {
        // don't profile internal php functions
        // show memory output
        \xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_MEMORY);
        $this->xhprof_on = true;
    }

    /**
     * Stop profiling and save and/or return results.
     *
     * @return array Profiling results
     */
    public function profileStop()
    {
        $data = array();
        if ($this->xhprof_on) {
            $this->xhprof_on = false;
            $data = xhprof_disable();

            if ($this->storage) {
                $this->storage->saveProfile($data);
            }
        }

        return $data;
    }
}
