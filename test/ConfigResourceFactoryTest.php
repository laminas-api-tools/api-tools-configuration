<?php

namespace LaminasTest\ApiTools\Configuration;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\ApiTools\Configuration\ConfigWriter;
use Laminas\ApiTools\Configuration\Factory\ConfigResourceFactory;
use Laminas\Config\Writer\WriterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function uniqid;

class ConfigResourceFactoryTest extends TestCase
{
    /** @var string */
    private const WRITER_SERVICE = ConfigWriter::class;

    /**
     * @var ContainerInterface|MockObject
     * @psalm-var ContainerInterface&MockObject
     */
    private $container;

    /** @var ConfigResourceFactory */
    private $factory;

    /**
     * @var WriterInterface|MockObject
     * @psalm-var WriterInterface&MockObject
     */
    private $writer;

    protected function setUp(): void
    {
        $this->writer    = $this->createMock(WriterInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->factory   = new ConfigResourceFactory();
    }

    public function testReturnsInstanceOfConfigResource(): void
    {
        $this->container->method('has')->with('config')->willReturn(false);
        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(self::WRITER_SERVICE)
            ->willReturn($this->writer);

        $factory        = $this->factory;
        $configResource = $factory($this->container);

        $this->assertInstanceOf(ConfigResource::class, $configResource);
    }

    public function testDefaultAttributesValues(): void
    {
        $this->container->method('has')->with('config')->willReturn(false);
        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(self::WRITER_SERVICE)
            ->willReturn($this->writer);

        $factory             = $this->factory;
        $configResource      = $factory($this->container);
        $configResourceClass = $configResource::class;
        $this->assertClassHasAttribute('config', $configResourceClass);
        $this->assertClassHasAttribute('fileName', $configResourceClass);
        $this->assertClassHasAttribute('writer', $configResourceClass);
        $this->assertSame([], $configResource->fetch(false));
    }

    public function testCustomConfigFileIsSet(): void
    {
        $configFile = uniqid('config_file');
        $config     = [
            'api-tools-configuration' => [
                'config_file' => $configFile,
            ],
        ];

        $this->container->method('has')->with('config')->willReturn(true);
        $this->container->expects(self::atLeastOnce())->method('get')->will(self::returnValueMap([
            ['config', $config],
            [self::WRITER_SERVICE, $this->writer],
        ]));

        $factory             = $this->factory;
        $configResource      = $factory($this->container);
        $configResourceClass = $configResource::class;

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

        $this->container->method('has')->with('config')->willReturn(true);
        $this->container->expects(self::atLeastOnce())->method('get')->will(self::returnValueMap([
            ['config', $config],
            [self::WRITER_SERVICE, $this->writer],
        ]));

        $factory             = $this->factory;
        $configResource      = $factory($this->container);
        $configResourceClass = $configResource::class;

        $expectedConfig = ['custom-configuration.foo' => 'bar'];
        $this->assertClassHasAttribute('config', $configResourceClass);
        $this->assertSame($expectedConfig, $configResource->fetch(false));
    }
}
