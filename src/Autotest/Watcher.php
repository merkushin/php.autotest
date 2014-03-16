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
        if ($this->lastCheckSum = $this->countCheckSum()) {
            return true;
        } else {
            return false;
        }
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
        $iterator = new \DirectoryIterator($path);
        /**
         * @var \DirectoryIterator $item
         */
        foreach ($iterator as $item) {
            if ($item->isDot()) {
                continue;
            } elseif ($item->isDir()) {
                $hashes = array_merge($hashes, $this->countCheckSumForPath($item->getPathname()));
            } else {
                if (preg_match('/\.php$/', $item->getFilename())) {
                    $hashes[$item->getPathname()] = md5_file($item->getPathname());
                }
            }
        }
        return $hashes;
    }
}
