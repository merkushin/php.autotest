<?php
namespace Autotest;

class Watcher
{
    private $path = '';
    private $filesCheckSums = array();

    private $lastCheckSum = '';
    private $lastChanges = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function watch()
    {
        return (bool)($this->lastCheckSum = $this->countCheckSum());
    }

    public function hasChanged()
    {
        $checkSum = $this->countCheckSum();
        $result = !($checkSum == $this->lastCheckSum);
        $this->lastCheckSum = $checkSum;
        return $result;
    }

    public function getChanges()
    {
        return $this->lastChanges;
    }

    private function countCheckSum()
    {
        $hashes = $this->countCheckSumForPath($this->path);

        $changes = array();
        foreach ($hashes as $filename => $hash) {
            if (!isset($this->filesCheckSums[$filename])) {
                $changes[] = $filename;
            } elseif ($hash != $this->filesCheckSums[$filename]) {
                $changes[] = $filename;
            }
        }
        $this->lastChanges = $changes;
        $this->filesCheckSums = $hashes;

        return md5(implode('', $hashes));
    }

    private function countCheckSumForPath($path)
    {
        $hashes = array();
        $Directory = new \RecursiveDirectoryIterator($path);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        $Regex = new \RegexIterator($Iterator, '/^.+\.(php|phtml|inc)$/i', \RecursiveRegexIterator::GET_MATCH);
        foreach ($Regex as $filename => $_) {
            $hashes[$filename] = md5_file($filename);
        }
        return $hashes;
    }
}
