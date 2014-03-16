<?php
namespace Autotest;

class Extractor
{
    private $srcPath;
    private $testsPath;
    private $paths;

    public function setPaths($paths)
    {
        $this->paths = $paths;
    }

    public function extract()
    {
        $this->checkSrcAndTestsPaths();
        $this->replaceSrcPathsWithTests();
        $this->sortPaths();
        return $this->findCommonPath();
    }

    private function checkSrcAndTestsPaths()
    {
        if (!$this->srcPath) {
            throw new \Exception('Source code path was not set');
        }

        if (!$this->testsPath) {
            throw new \Exception('Tests path was not set');
        }
    }

    private function replaceSrcPathsWithTests()
    {
        foreach ($this->paths as $index => $path) {
            if (substr($path, 0, strlen($this->srcPath) + 1) == $this->srcPath . DIRECTORY_SEPARATOR) {
                $replacedPath = str_replace($this->srcPath, $this->testsPath, $path);
                $info = pathinfo($replacedPath);
                $processedPath = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . 'Test.' . $info['extension'];
                if (file_exists($processedPath)) {
                    $this->paths[$index] = $processedPath;
                } else {
                    unset($this->paths[$index]);
                }
            }
        }
    }

    private function sortPaths()
    {
        $paths = array_filter($this->paths);
        usort($paths, function ($a, $b) {
            return strcmp($a, $b);
        });
        $this->paths = $paths;
    }

    private function findCommonPath()
    {
        if (count($this->paths) == 0) {
            return $this->testsPath;
        }

        $shortestPath = array_shift($this->paths);
        $found = false;
        while (!$found) {
            $found = true;
            foreach ($this->paths as $path) {
                if ($shortestPath == $path) {
                    continue;
                } else {
                    $compareWith = substr($path, 0, strlen($shortestPath) + 1);
                    if ($shortestPath . DIRECTORY_SEPARATOR == $compareWith) {
                        continue;
                    } else {
                        $found = false;
                    }
                }
            }

            if (!$found) {
                $shortestPath = ltrim($shortestPath, DIRECTORY_SEPARATOR);
                $pathElements = explode(DIRECTORY_SEPARATOR, $shortestPath);
                $pathElements = array_slice($pathElements, 0, count($pathElements) - 1);
                $shortestPath = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathElements);
            }

            if ($shortestPath == DIRECTORY_SEPARATOR) {
                break;
            }
        }
        return $shortestPath;
    }

    /**
     * @return string
     */
    public function getSrcPath()
    {
        return $this->srcPath;
    }

    /**
     * @param string $srcPath
     */
    public function setSrcPath($srcPath)
    {
        $this->srcPath = $srcPath;
    }

    /**
     * @return string
     */
    public function getTestsPath()
    {
        return $this->testsPath;
    }

    /**
     * @param string $testsPath
     */
    public function setTestsPath($testsPath)
    {
        $this->testsPath = $testsPath;
    }
}