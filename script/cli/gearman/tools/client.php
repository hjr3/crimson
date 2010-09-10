<?php
function help($e=0) {
    $o = <<<EOT
Key/value gearman client

Primarily used for development and testing.  Makes it easier to
send parameters to a Gearman worker.

-f          Gearman worker function to call
-h          This help message
-H          Gearman host (default: localhost)
-p          Gearman port (default: 4730)
--params    List of parameters to send worker in key=value format
    Example: --params foo=1 bar=2 baz='hi mom'
EOT;

    echo "{$o}\n\n";
    exit($e);
}

/**
 * Parse parameters in key=value format
 * 
 * @param array $options List of options used to search for parameters
 * @return array A list of parameters
 */
function parse_params(array $options) 
{
    $start = false;
    $params = array();

    foreach ($options as $opt) {

        if ($opt == '--params') {
            $start = true;
            continue;
        }

        if ($start) {

            if ($opt[0] == '-') {
                echo "Parsing stopped: Found cli param: '{$opt}'", PHP_EOL, PHP_EOL;
                break;
            }

            if (strpos($opt, '=') === false) {
                echo "Skipping invalid parameter {$param}", PHP_EOL;
                continue;
            }

            list($key, $value) = explode('=', $opt);
            $params[$key] = $value;
        }
    }

    return $params;
}

$options = getopt('f:hH:p:');

if (array_key_exists('h', $options)) {
    help();
}

if (!array_key_exists('f', $options)) {
    help(1);
}

if (!array_key_exists('H', $options)) {
    $options['H'] = 'localhost';
}

if (!array_key_exists('p', $options)) {
    $options['p'] = 4730;
}

$workload = parse_params($argv);

$host = $options['H'];
$port = $options['p'];
$function = $options['f'];
$workload = serialize($workload);

echo PHP_EOL, 'Starting gearman job request:', PHP_EOL;
echo "gearman -h {$host} -p {$port} -f {$function} '{$workload}'", PHP_EOL;

$client = new GearmanClient;
$client->addServer($host, $port);
$result = $client->do($function, $workload);
