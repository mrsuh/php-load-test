<?php

$containers = [];

$lines = explode(PHP_EOL, file_get_contents('/Users/newuser/web/test/php-load-test/docker/react-php/log.txt'));
foreach ($lines as $line) {
    preg_match('/(php_\d+)_.*http:\/\/php:9000\//', $line, $match);

    if (count($match) !== 2) {
        continue;
    }

    $container = $match[1];

    echo 'SUCCESS ' . $container . PHP_EOL;


    if (in_array($container, $containers)) {
        continue;
    }

    $containers[] = $container;
}

echo 'COUNT:' . count($containers) . PHP_EOL;