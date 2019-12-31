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
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Prophecy\ProphecyInterface;

class ConfigResourceFactoryTest extends TestCase
{
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

    protected function setUp()
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

        $this->assertAttributeSame([], 'config', $configResource);
        $this->assertAttributeSame('config/autoload/development.php', 'fileName', $configResource);
        $this->assertAttributeSame($this->writer, 'writer', $configResource);
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

        $this->assertAttributeSame($config, 'config', $configResource);
        $this->assertAttributeSame($configFile, 'fileName', $configResource);
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

        $this->assertAttributeSame($config, 'config', $configResource);
    }
}
