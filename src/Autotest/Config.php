<?php
namespace Autotest;

class Config
{
    private $applicationPath = '';
    private $cmd = 'phpunit';
    private $srcPath = '';
    private $testsPath = '';
    private $testSuffix = 'Test';
    private $timeout = 1;

    public function __construct($applicationPath)
    {
        $this->applicationPath = rtrim($applicationPath, DIRECTORY_SEPARATOR);
        $this->srcPath = $this->applicationPath . DIRECTORY_SEPARATOR . 'src';
        $this->testsPath = $this->applicationPath . DIRECTORY_SEPARATOR . 'tests';
    }

    public function setOptions($options)
    {
        if (!empty($options['cmd'])) {
            $this->cmd = $options['cmd'];
        }

        if (!empty($options['src_path'])) {
            $srcPath = realpath($options['src_path']);
            if ($srcPath === false) {
                throw new \Exception('Source path not found: ' . $options['src_path']);
            }
            $this->srcPath = $srcPath;
        }

        if (!empty($options['tests_path'])) {
            $testsPath = realpath($options['tests_path']);
            if ($testsPath === false) {
                throw new \Exception('Tests path not found: ' . $options['tests_path']);
            }
            $this->testsPath = $testsPath;
        }

        if (!empty($options['suffix'])) {
            $this->testSuffix = $options['suffix'];
        }

        if (!empty($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    public function getApplicationPath()
    {
        return $this->applicationPath;
    }

    /**
     * Get PHPUnit CLI command
     *
     * @return string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * Get path to source code
     *
     * @return string
     */
    public function getSrcPath()
    {
        return $this->srcPath;
    }

    /**
     * Get path to tests directory
     *
     * @return string
     */
    public function getTestsPath()
    {
        return $this->testsPath;
    }

    /**
     * @return string
     */
    public function getTestSuffix()
    {
        return $this->testSuffix;
    }

    /**
     * Get timeout before counting checksum
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}