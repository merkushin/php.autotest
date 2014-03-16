<?php
namespace Autotest;

require_once realpath(__DIR__ . '/../../src/Autotest/Watcher.php');

class WatcherTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        if (file_exists($this->getTestFilename())) {
            unlink($this->getTestFilename());
        }
    }

    public function testClassExists()
    {
        $this->assertTrue(class_exists('Autotest\Watcher'), 'Class \Autotest\Watcher does not exist');
    }

    public function testSetPath()
    {
        $watcher = new Watcher(__DIR__);
        $this->assertEquals(__DIR__, $watcher->getPath(), 'Path was set incorrectly');
    }

    public function testWatch()
    {
        $watcher = new Watcher(__DIR__);
        $this->assertTrue($watcher->watch(), 'watch method should return true');
    }

    public function testHasChanged()
    {
        $this->assertFalse(file_exists($this->getTestFilename()), 'File should not exist');
        $watcher = new Watcher($this->getFixturesPath());
        $watcher->watch();
        $this->assertFalse($watcher->hasChanged(), 'Directory did not cange');
        file_put_contents($this->getTestFilename(), 'xyz');
        $this->assertTrue($watcher->hasChanged(), 'Directory was changed');
    }

    public function testGetChanges()
    {
        $watcher = new Watcher($this->getFixturesPath());
        $watcher->watch();
        file_put_contents($this->getTestFilename(), 'xyz');
        $this->assertTrue($watcher->hasChanged(), 'File was changed');
        $this->assertEquals(array($this->getTestFilename()), $watcher->getChanges());
    }

    private function getTestFilename()
    {
        return $this->getFixturesPath() .  '/test.php';
    }

    private function getFixturesPath()
    {
        return __DIR__ . '/../../tests_fixtures';
    }
}
