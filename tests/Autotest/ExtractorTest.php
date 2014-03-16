<?php
namespace Autotest;

require_once __DIR__ . '/../../src/Autotest/Extractor.php';

class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Autotest\Extractor'), 'Class \Autotest\Extractor does not exist');
    }

    public function testSetPaths()
    {
        $extractor = new Extractor();
        $this->assertTrue(method_exists($extractor, 'setPaths'), 'Extractor has no method setPaths');
    }

    /**
     * @expectedException \Exception
     */
    public function testThrowsExceptionWithoutSrcPath()
    {
        $extractor = new Extractor();
        $extractor->setTestsPath('test');
        $extractor->extract();
    }

    /**
     * @expectedException \Exception
     */
    public function testThrowsExceptionWithoutTestsPath()
    {
        $extractor = new Extractor();
        $extractor->setSrcPath('src');
        $extractor->extract();
    }

    public function testExtractWithCommonPartInPath()
    {
        $paths = $this->getPaths();
        $extractor = new Extractor();
        $extractor->setSrcPath($paths['src']);
        $extractor->setTestsPath($paths['tests']);
        $extractor->setPaths(array(
            '/my/path/one/two/three',
            '/my/path/two',
        ));

        $this->assertEquals('/my/path', $extractor->extract(), 'Invalid common path');
    }

    public function testExtractWithoutCommonPartInPath()
    {
        $paths = $this->getPaths();
        $extractor = new Extractor();
        $extractor->setSrcPath($paths['src']);
        $extractor->setTestsPath($paths['tests']);
        $extractor->setPaths(array(
            '/one/path/',
            '/two/path',
            '/another/one/path/file.php'
        ));

        $this->assertEquals('/', $extractor->extract());
    }

    public function testSetSrcAndTestsPaths()
    {
        $extractor = new Extractor();
        $extractor->setSrcPath('/my/app/src');
        $extractor->setTestsPath('/my/app/tests');

        $this->assertEquals('/my/app/src', $extractor->getSrcPath());
        $this->assertEquals('/my/app/tests', $extractor->getTestsPath());
    }

    public function testExtractWithSrcPath()
    {
        $appPath = realpath(__DIR__ . '/../../');
        $srcPath = $appPath . '/src';
        $testsPath = $appPath . '/tests';

        $extractor = new Extractor();
        $extractor->setSrcPath($srcPath);
        $extractor->setTestsPath($testsPath);
        $extractor->setPaths(array(
            $srcPath . '/Autotest/Config.php',
        ));

        $this->assertEquals($testsPath . '/Autotest/ConfigTest.php', $extractor->extract());
    }

    public function testExtractProcessedPathShouldExist()
    {
        $paths = $this->getPaths();

        $extractor = new Extractor();
        $extractor->setSrcPath($paths['src']);
        $extractor->setTestsPath($paths['tests']);
        $extractor->setPaths(array(
            $paths['src'] . '/Autotest/NotExists.php',
        ));
        $this->assertEquals($paths['tests'], $extractor->extract());

    }

    public function testExtractReturnsTestsPathIfPathsAreEmpty()
    {
        $paths = $this->getPaths();

        $extractor = new Extractor();
        $extractor->setSrcPath($paths['src']);
        $extractor->setTestsPath($paths['tests']);
        $extractor->setPaths(array());
        $this->assertEquals($paths['tests'], $extractor->extract());

    }

    private function getPaths()
    {
        $appPath = realpath(__DIR__ . '/../../');
        return array(
            'src' =>  $appPath . '/src',
            'tests' => $appPath . '/tests',
        );
    }
}