<?php

namespace LaminasTest\ApiTools\Configuration;

use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Configuration\ResourceFactory;
use PHPUnit\Framework\TestCase;

class ResourceFactoryTest extends TestCase
{
    /** @var TestAsset\ConfigWriter */
    protected $testWriter;

    /** @var ResourceFactory */
    protected $resourceFactory;

    protected function setUp(): void
    {
        $this->resourceFactory = new ResourceFactory(
            $this->createMock(ModuleUtils::class),
            $this->testWriter  = new TestAsset\ConfigWriter()
        );
    }

    public function testCreateConfigResource(): void
    {
        $resource = $this->resourceFactory->createConfigResource(['foo' => 'bar'], '/path/to/file.php');
        $this->assertInstanceOf(ConfigResource::class, $resource);
        $this->assertEquals(['foo' => 'bar'], $resource->fetch(true));
        $resource->overWrite(['foo' => 'baz']);

        $this->assertEquals('/path/to/file.php', $this->testWriter->writtenFilename);
        $this->assertEquals(['foo' => 'baz'], $this->testWriter->writtenConfig);
    }
}
