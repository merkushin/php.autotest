<?php
namespace Autotest;

class Runner
{
    private $cmd = 'phpunit';

    public function __construct($cmd = null)
    {
        if ($cmd) {
            $this->cmd = $cmd;
        }
    }

    public function getCmd()
    {
        return $this->cmd;
    }

    public function run($path = null)
    {
        $command = $this->buildCommandString($path);
        return $command . PHP_EOL . $this->runPhpUnit($command);
    }

    private function runPhpUnit($command)
    {
        exec($command, $output);
        return implode(PHP_EOL, $output);
    }

    private function buildCommandString($path)
    {
        return $this->cmd . ' ' . ($path ? $path : '');
    }
}