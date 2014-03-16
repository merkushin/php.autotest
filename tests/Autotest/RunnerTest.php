<?php
namespace Autotest;

require_once realpath(__DIR__ . '/../../src/Autotest/Runner.php');

class RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Autotest\Runner'), 'Class \Autotest\Runner does not exist');
    }

    public function testSetCommand()
    {
        $runner = new Runner(array());
        $this->assertEquals('phpunit', $runner->getCmd(), 'Command was set incorrectly');
    }

    public function testRunWithNoParams()
    {
        $runner = new Runner($this->getValidCmd());
        $this->assertNotEmpty($runner->run(), 'Runner::run returned empty result');
    }

    public function testRunResultContainsPHPUnitMessage()
    {
        $runner = new Runner($this->getValidCmd());
        $result = $runner->run();
        $match = preg_match('/PHPUnit ([\d\.]+) by Sebastian Bergmann./', $result);
        $this->assertTrue($match == 1, 'Has no message from PHPUnit');
    }

    public function testRunWithFilePath()
    {
        $runner = new Runner($this->getValidCmd());
        $result = $runner->run(
            $this->getFixturesPath() . '/Example1Test.php'
        );
        $match = preg_match('/OK \(1 test, 1 assertion\)/', $result);
        $this->assertTrue($match == 1, 'Has no valid result');
    }

    public function testRunWithDirectoryPath()
    {
        $runner = new Runner($this->getValidCmd());
        $result = $runner->run(
            $this->getFixturesPath()
        );
        $match = preg_match('/OK \(2 tests, 2 assertions\)/', $result);
        $this->assertTrue($match == 1, 'Has no valid result');
    }

    private function getValidCmd()
    {
        return 'phpunit --no-configuration';
    }

    private function getFixturesPath()
    {
        return __DIR__ . '/../../tests_fixtures';
    }
}