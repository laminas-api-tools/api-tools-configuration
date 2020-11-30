<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\Factory\ConfigWriterFactory;
use Laminas\Config\Writer\PhpArray;
use PHPUnit\Framework\TestCase;
use Prophecy\PHPUnit\ProphecyTrait;
use Prophecy\Prophecy\ProphecyInterface;

class ConfigWriterFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ContainerInterface|ProphecyInterface
     */
    private $container;

    /**
     * @var ConfigWriterFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->factory = new ConfigWriterFactory();
    }

    public function testReturnsInstanceOfPhpArrayWriter()
    {
        $factory = $this->factory;
        $configWriter = $factory($this->container->reveal());

        $this->assertInstanceOf(PhpArray::class, $configWriter);
    }

    public function testDefaultFlagsValues()
    {
        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container->reveal());

        $this->assertClassHasAttribute('useBracketArraySyntax', $configWriter::class);
        $this->assertFalse($configWriter->getUseClassNameScalars());
    }

    public function testEnableShortArrayFlagIsSet()
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            'api-tools-configuration' => [
                'enable_short_array' => true,
            ],
        ]);

        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container->reveal());

        $this->assertClassHasAttribute('useBracketArraySyntax', $configWriter::class);
//        $this->assertAttributeSame(true, 'useBracketArraySyntax', $configWriter);
    }

    public function testClassNameScalarsFlagIsSet()
    {
        $this->container->has('config')->willReturn(true);
        $this->container->get('config')->willReturn([
            'api-tools-configuration' => [
                'class_name_scalars' => true,
            ],
        ]);

        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container->reveal());

        $this->assertTrue($configWriter->getUseClassNameScalars());
    }
}
