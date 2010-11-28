<?php

namespace crimson\cli\gearman\tools\worker;

function help($e=0) {
    $o = <<<EOT
Simple bootstrap for a gearman worker.

Primarily used for development and testing.  This does not replace the 
GearmanManager, but instead simplifies the process of attaching a
gearman worker to the gearman server.

-d          Script directory (default: .)
-f          Gearman worker function
-s          Gearman worker script (default: \$function . ".php")
-h          This help message
-H          Gearman server (default: localhost)
-p          Gearman port (default: 4730)
EOT;

    echo "{$o}\n\n";
    exit($e);
}

function log($msg)
{
    echo "{$msg}", PHP_EOL;

}

function work($job) 
{
    global $function;

    $handle = $job->handle();
    log("Received job: {$handle}");

    $result = $function($job);

    log("Finished job: {$handle}");

    return $result;
}

$options = getopt('d:f:s:H:p:');

if (array_key_exists('h', $options)) {
    help();
}

if (!array_key_exists('d', $options)) {
    $options['d'] = '.';
}


if (!array_key_exists('f', $options)) {
    help(1);
}

if (!array_key_exists('H', $options)) {
    $options['H'] = '127.0.0.1';
}

if (!array_key_exists('p', $options)) {
        $options['p'] = 4730;
}

$function = $options['f'];
$dir = $options['d'];
$host = $options['H'];
$port = $options['p'];

if (!array_key_exists('s', $options)) {
    $script = "{$function}.php";
} else {
    $script = $options['s'];
}

$file = "{$dir}/{$script}";

require $file;

$worker = new \GearmanWorker;
log("Gearman worker running... press Ctrl-c to stop");
$worker->addServer($host, $port);
$worker->addFunction($function, __NAMESPACE__ . '\work');
log("Listening for function: {$function}");

while($worker->work()) {
    if ($worker->returnCode() != GEARMAN_SUCCESS) {
        log("return_code: ", $worker->returnCode());
        break;
    }
}
