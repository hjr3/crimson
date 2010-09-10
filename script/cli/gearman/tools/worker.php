<?php

function help($e=0) {
    $o = <<<EOT
Simple bootstrap for a gearman worker.

Primarily used for development and testing.  This does not replace the 
GearmanManager, but instead simplifies the process of attaching a
gearman worker to the gearman server.

-d  Script directory (defaults to '.')
-f  Gearman worker function
-h  This help message
EOT;

    echo "{$o}\n\n";
    exit($e);
}

$options = getopt('d:f:');

if (array_key_exists('h', $options)) {
    help();
}

if (!array_key_exists('d', $options)) {
    $options['d'] = __DIR__;
}

if (!array_key_exists('f', $options)) {
    help(1);
}

$function = $options['f'];
$dir = $options['d'];
$filename = "/{$function}.php";

include $dir . $filename;

$worker = new GearmanWorker;
$worker->addServer();
$worker->addFunction($function, 'work');
echo "Gearman worker running... press Ctrl-c to stop\n";

while($worker->work()) {
    if ($worker->returnCode() != GEARMAN_SUCCESS) {
        echo "return_code: " . $worker->returnCode() . "\n";
        break;
    }
}

function work($job) 
{
    global $function;

    echo "Received job: " . $job->handle() . "\n";

    return $function($job);
}
