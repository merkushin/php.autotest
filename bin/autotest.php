#!/usr/bin/env php
<?php
require_once __DIR__ . '/../src/Autotest/Config.php';
require_once __DIR__ . '/../src/Autotest/Watcher.php';
require_once __DIR__ . '/../src/Autotest/Extractor.php';
require_once __DIR__ . '/../src/Autotest/Runner.php';

define('MICROSECOND', 1000000);

$config = new \Autotest\Config(getcwd());
$config->parse($argv);

$watchers = array();
if ($config->getTestsPath()) {
    $watchers[] = new \Autotest\Watcher($config->getTestsPath());
}
if ($config->getSrcPath()) {
    $watchers[] = new \Autotest\Watcher($config->getSrcPath());
}
if (!$watchers) {
    echo 'Nothing to watch. Please specify src and/or test directories' . PHP_EOL;
    return;
}

/**
 * @var \Autotest\Watcher $watcher
 */
foreach ($watchers as $watcher) {
    $watcher->watch();
}

$runner = new \Autotest\Runner($config->getCmd());
$extractor = new \Autotest\Extractor();
$extractor->setSrcPath($config->getSrcPath());
$extractor->setTestsPath($config->getTestsPath());

try {
while (true) {
    $changedPaths = array();
    foreach ($watchers as $watcher) {
        if ($watcher->hasChanged()) {
            $changedPaths = array_merge($changedPaths, $watcher->getChanges());
        }
    }

    if (count($changedPaths)) {
        $extractor->setPaths($changedPaths);
        $path = $extractor->extract();
        $result = $runner->run($path);
        echo $result . str_repeat(PHP_EOL, 2);
    }

    usleep($config->getTimeout() * MICROSECOND);
}
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}