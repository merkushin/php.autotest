<?php
namespace AutotestTest;

use Autotest\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Autotest\Config'), 'Class Autotest\Config does not exist');
    }

    public function testSetApplicationPath()
    {
        $config = new Config($this->getApplicationPath() . '/');
        $this->assertEquals($this->getApplicationPath(), $config->getApplicationPath());
    }

    public function testDefaultPaths()
    {
        $config = new Config($this->getApplicationPath());
        $this->assertEquals($this->getApplicationPath() . '/tests', $config->getTestsPath());
        $this->assertEquals($this->getApplicationPath() . '/src', $config->getSrcPath());
    }

    public function testSetOptions()
    {
        $config = new Config($this->getApplicationPath());
        $config->setOptions(array(
            'cmd' => '/sebastian/phpunit',
            'src_path' => $this->getApplicationPath() . '/src',
            'tests_path' => $this->getApplicationPath() . '/tests',
            'timeout' => '10',
        ));

        $this->assertEquals('/sebastian/phpunit', $config->getCmd(), 'Command does not match');
        $this->assertEquals($this->getApplicationPath() . '/src', $config->getSrcPath(), 'Source code path does not match');
        $this->assertEquals($this->getApplicationPath() . '/tests', $config->getTestsPath(), 'Tests path does not match');
        $this->assertEquals(10, $config->getTimeout(), 'Timeout does not match');
    }

    public function testRelativePaths()
    {
        chdir($this->getApplicationPath());
        $config = new Config($this->getApplicationPath());
        $config->setOptions(array(
            'src_path' => 'src/',
            'tests_path' => 'tests/',
        ));

        $this->assertEquals($this->getApplicationPath() . '/src', $config->getSrcPath(), 'Source code path does not match');
        $this->assertEquals($this->getApplicationPath() . '/tests', $config->getTestsPath(), 'Tests path does not match');
        chdir(__DIR__);
    }

    private function getApplicationPath()
    {
        return realpath(__DIR__ . '/../..');
    }
}