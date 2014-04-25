<?php
namespace Autotest;

class Config
{
    private $applicationPath = '';
    private $cmd = 'phpunit';
    private $srcPath = '';
    private $testsPath = '';
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
            $srcPath = $options['src_path'];
            if ($this->isPathRelative($srcPath)) {
                $srcPath = $this->convertRelativePathToAbsolute($srcPath);
            }
            $this->srcPath = $srcPath;
        }

        if (!empty($options['tests_path'])) {
            $testsPath = $options['tests_path'];
            if ($this->isPathRelative($testsPath)) {
                $testsPath = $this->convertRelativePathToAbsolute($testsPath);
            }
            $this->testsPath = $testsPath;
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
     * Get timeout before counting checksum
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Check if path is relative
     *
     * @param string $path
     * @return bool
     */
    private function isPathRelative($path)
    {
        if (substr($path, 0, 1) == DIRECTORY_SEPARATOR) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Do path absolute: prepend relative path with application path
     *
     * @param string $path
     * @return string
     */
    private function convertRelativePathToAbsolute($path)
    {
        return $this->applicationPath . DIRECTORY_SEPARATOR .
            rtrim($path, DIRECTORY_SEPARATOR);
    }
}