<?php
namespace Autotest;

class Autotest
{
    /**
     * @var \Autotest\Config
     */
    private $config;
    private $watchers = array();

    const MICROSECOND = 1000000;

    public function __construct($config)
    {
        $this->config = $config;
        $this->addWatchers();
    }

    private function addWatchers()
    {
        if ($this->config->getTestsPath()) {
            $this->watchers[] = new Watcher($this->config->getTestsPath());
        }
        if ($this->config->getSrcPath()) {
            $this->watchers[] = new Watcher($this->config->getSrcPath());
        }
        if (count($this->watchers) == 0) {
            throw new \Exception('Nothing to watch. Please specify src and/or test directories');
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getWatchers()
    {
        return $this->watchers;
    }

    public function run()
    {
        /**
         * @var \Autotest\Watcher $watcher
         */
        foreach ($this->watchers as $watcher) {
            $watcher->watch();
        }

        $runner = new Runner($this->config->getCmd());
        $extractor = new Extractor();
        $extractor->setSrcPath($this->config->getSrcPath());
        $extractor->setTestsPath($this->config->getTestsPath());
        $extractor->setTestSuffix($this->config->getTestSuffix());

        while (true) {
            $changedPaths = array();
            foreach ($this->watchers as $watcher) {
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

            usleep($this->config->getTimeout() * self::MICROSECOND);
        }
    }
}