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

    public function parse($args)
    {
        array_shift($args);
        foreach ($args as $arg) {
            switch (true) {
                case preg_match('#^--cmd=([\w/]+)$#', $arg, $matches) :
                    $this->cmd = $matches[1];
                    break;
                case preg_match('#^--src_path=([\w/]+)$#', $arg, $matches) :
                    $srcPath = $matches[1];
                    if ($this->isPathRelative($srcPath)) {
                        $srcPath = $this->convertRelativePathToAbsolute($srcPath);
                    }
                    $this->srcPath = $srcPath;
                    break;
                case preg_match('#^--tests_path=([\w/]+)$#', $arg, $matches) :
                    $testsPath = $matches[1];
                    if ($this->isPathRelative($testsPath)) {
                        $testsPath = $this->convertRelativePathToAbsolute($testsPath);
                    }
                    $this->testsPath = $testsPath;
                    break;
                case preg_match('#^--timeout=(\d+)$#', $arg, $matches) :
                    $this->timeout = (int)$matches[1];
                    break;
            }
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