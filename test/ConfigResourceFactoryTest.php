<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\ApiTools\Configuration\Factory\ConfigResourceFactory;
use Laminas\Config\Writer\WriterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigResourceFactoryTest extends TestCase
{
    /** @var string */
    private const WRITER_SERVICE = 'Laminas\ApiTools\Configuration\ConfigWriter';

    /**
     * @var ContainerInterface|MockObject
     * @psalm-var ContainerInterface&MockObject
     */
    private $container;

    /**
     * @var ConfigResourceFactory
     */
    private $factory;

    /**
     * @var WriterInterface|MockObject
     * @psalm-var WriterInterface&MockObject
     */
    private $writer;

    protected function setUp(): void
    {
        $this->writer = $this->createMock(WriterInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->factory = new ConfigResourceFactory();
    }

    public function testReturnsInstanceOfConfigResource(): void
    {
        $this->container->expects($this->atLeastOnce())->method('has')->with('config')->willReturn(false);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with(self::WRITER_SERVICE)
            ->willReturn($this->writer);

        $factory = $this->factory;
        $configResource = $factory($this->container);

        $this->assertInstanceOf(ConfigResource::class, $configResource);
    }

    public function testDefaultAttributesValues(): void
    {
        $this->container->expects($this->atLeastOnce())->method('has')->with('config')->willReturn(false);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->with(self::WRITER_SERVICE)
            ->willReturn($this->writer);

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container);
        $configResourceClass = get_class($configResource);
        $this->assertClassHasAttribute('config', $configResourceClass);
        $this->assertClassHasAttribute('fileName', $configResourceClass);
        $this->assertClassHasAttribute('writer', $configResourceClass);
        $this->assertSame([], $configResource->fetch(false));
    }

    public function testCustomConfigFileIsSet(): void
    {
        $configFile = uniqid('config_file');
        $config = [
            'api-tools-configuration' => [
                'config_file' => $configFile,
            ],
        ];

        $this->container->expects($this->once())->method('has')->with('config')->willReturn(true);
        $this->container->expects($this->atLeastOnce())->method('get')->will($this->returnValueMap([
            ['config', $config],
            [self::WRITER_SERVICE, $this->writer],
        ]));

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container);
        $configResourceClass = get_class($configResource);

        $this->assertClassHasAttribute('config', $configResourceClass);
        $this->assertClassHasAttribute('fileName', $configResourceClass);
        $configValues = $configResource->fetch(false);
        $this->assertSame($configValues['api-tools-configuration.config_file'], $configFile);
    }

    public function testCustomConfigurationIsPassToConfigResource(): void
    {
        $config = [
            'custom-configuration' => [
                'foo' => 'bar',
            ],
        ];

        $this->container->expects($this->atLeastOnce())->method('has')->with('config')->willReturn(true);
        $this->container->expects($this->atLeastOnce())->method('get')->will($this->returnValueMap([
            ['config', $config],
            [self::WRITER_SERVICE, $this->writer],
        ]));

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container);
        $configResourceClass = get_class($configResource);

        $expectedConfig = ['custom-configuration.foo' => 'bar'];
        $this->assertClassHasAttribute('config', $configResourceClass);
        $this->assertSame($expectedConfig, $configResource->fetch(false));
    }
}
