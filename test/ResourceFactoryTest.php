<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration;

use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Configuration\ResourceFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceFactoryTest extends TestCase
{
    protected $testWriter = null;
    protected $resourceFactory = null;

    public function setup()
    {
        $this->resourceFactory = new ResourceFactory(
            $this->getMock('Laminas\ApiTools\Configuration\ModuleUtils', [], [], '', false),
            $this->testWriter = new TestAsset\ConfigWriter()
        );
    }

    public function testCreateConfigResource()
    {
        $resource = $this->resourceFactory->createConfigResource(['foo' => 'bar'], '/path/to/file.php');
        $this->assertInstanceOf('Laminas\ApiTools\Configuration\ConfigResource', $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->fetch(true));
        $resource->overWrite(['foo' => 'baz']);

        $this->assertEquals('/path/to/file.php', $this->testWriter->writtenFilename);
        $this->assertEquals(['foo' => 'baz'], $this->testWriter->writtenConfig);
    }
}
