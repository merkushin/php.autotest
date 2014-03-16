#!/usr/bin/env php
<?php
require_once __DIR__ . '/../src/Autotest/Config.php';
require_once __DIR__ . '/../src/Autotest/Autotest.php';

try {
    $config = new \Autotest\Config(getcwd());
    $config->parse($argv);

    $autotest = new \Autotest\Autotest($config);
    $autotest->run();
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}