#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $application = new \Autotest\Console\Application();
    $application->run();
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}