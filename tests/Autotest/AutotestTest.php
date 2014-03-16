<?php
namespace Autotest;

require_once __DIR__ . '/../../src/Autotest/Autotest.php';

class AutotestTest extends \PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Autotest\Autotest'));
    }

    public function testSetConfig()
    {
        $config = $this->getConfigMock();
        $autotest = new Autotest($config);
        $this->assertEquals($config, $autotest->getConfig());
    }

    public function testGetWatchers()
    {
        $config = $this->getConfigMock();
        $autotest = new Autotest($config);
        $this->assertEquals(2, count($autotest->getWatchers()));
    }

    public function testRun()
    {
        //
    }

    private function getConfigMock()
    {
        $mock = $this->getMock('Autotest\Config', array(
            'getTestsPath',
            'getSrcPath',

        ));
        $mock->expects($this->any())
            ->method('getTestsPath')
            ->will($this->returnValue('/some/path/tests'));
        $mock->expects($this->any())
            ->method('getSrcPath')
            ->will($this->returnValue('/some/path/src'));
        return $mock;
    }
}