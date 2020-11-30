<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\ApiTools\Configuration\ConfigWriter;
use Laminas\ApiTools\Configuration\Factory\ConfigResourceFactory;
use Laminas\Config\Writer\WriterInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ProphecyInterface;
use Prophecy\PhpUnit\ProphecyTrait;

class ConfigResourceFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ContainerInterface|ProphecyInterface
     */
    private $container;

    /**
     * @var ConfigResourceFactory
     */
    private $factory;

    /**
     * @var WriterInterface
     */
    private $writer;

    protected function setUp(): void
    {
        $this->writer = $this->prophesize(WriterInterface::class)->reveal();
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->container->get(ConfigWriter::class)->willReturn($this->writer);
        $this->factory = new ConfigResourceFactory();
    }

    public function testReturnsInstanceOfConfigResource()
    {
        $this->container->has('config')->willReturn(false);

        $factory = $this->factory;
        $configResource = $factory($this->container->reveal());

        $this->assertInstanceOf(ConfigResource::class, $configResource);
    }

    public function testDefaultAttributesValues()
    {
        $this->container->has('config')->willReturn(false);

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container->reveal());
        $this->assertClassHasAttribute('config', $configResource::class);
        $this->assertClassHasAttribute('fileName', $configResource::class);
        $this->assertClassHasAttribute('writer', $configResource::class);
        $this->assertSame([], $configResource->fetch(false));
    }

    public function testCustomConfigFileIsSet()
    {
        $configFile = uniqid('config_file');
        $config = [
            'api-tools-configuration' => [
                'config_file' => $configFile,
            ],
        ];

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn($config);

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container->reveal());

        $this->assertClassHasAttribute('config', $configResource::class);
        $this->assertClassHasAttribute('fileName', $configResource::class);
        $configValues = $configResource->fetch(false);
        $this->assertSame($configValues['api-tools-configuration.config_file'], $configFile);
    }

    public function testCustomConfigurationIsPassToConfigResource()
    {
        $config = [
            'custom-configuration' => [
                'foo' => 'bar',
            ],
        ];

        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn($config);

        $factory = $this->factory;

        /** @var ConfigResource $configResource */
        $configResource = $factory($this->container->reveal());

        $expectedConfig = ['custom-configuration.foo' => 'bar'];
        $this->assertClassHasAttribute('config', $configResource::class);
        $this->assertSame($expectedConfig, $configResource->fetch(false));
    }
}
