<?php
namespace Autotest;

require_once __DIR__ . '/../../src/Autotest/Config.php';

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

    public function testParse()
    {
        $config = new Config($this->getApplicationPath());
        $config->parse(array(
            'script.php',
            '--cmd=/sebastian/phpunit',
            '--src_path=/src/of/my/code',
            '--tests_path=/tests/for/my/code',
            '--timeout=10',
        ));

        $this->assertEquals('/sebastian/phpunit', $config->getCmd(), 'Command does not match');
        $this->assertEquals('/src/of/my/code', $config->getSrcPath(), 'Source code path does not match');
        $this->assertEquals('/tests/for/my/code', $config->getTestsPath(), 'Tests path does not match');
        $this->assertEquals(10, $config->getTimeout(), 'Timeout does not match');
    }

    public function testRelativePaths()
    {
        $config = new Config($this->getApplicationPath());
        $config->parse(array(
            'script.php',
            '--src_path=src/',
            '--tests_path=tests/',
        ));

        $this->assertEquals($this->getApplicationPath() . '/src', $config->getSrcPath(), 'Source code path does not match');
        $this->assertEquals($this->getApplicationPath() . '/tests', $config->getTestsPath(), 'Tests path does not match');
    }

    private function getApplicationPath()
    {
        return '/home/my/application';
    }
}