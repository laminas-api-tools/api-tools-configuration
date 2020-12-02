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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigWriterFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface|MockObject
     * @psalm-var ContainerInterface&MockObject
     */
    private $container;

    /**
     * @var ConfigWriterFactory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->factory = new ConfigWriterFactory();
    }

    public function testReturnsInstanceOfPhpArrayWriter(): void
    {
        $factory = $this->factory;
        $configWriter = $factory($this->container);

        $this->assertInstanceOf(PhpArray::class, $configWriter);
    }

    public function testDefaultFlagsValues(): void
    {
        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container);

        $this->assertClassHasAttribute('useBracketArraySyntax', get_class($configWriter));
        $this->assertFalse($configWriter->getUseClassNameScalars());
    }

    public function testEnableShortArrayFlagIsSet(): void
    {
        $this->container->expects($this->atLeastOnce())->method('has')->with('config')->willReturn(true);
        $this->container->expects($this->atLeastOnce())->method('get')->with('config')->willReturn([
            'api-tools-configuration' => [
                'enable_short_array' => true,
            ],
        ]);

        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container);

        $this->assertClassHasAttribute('useBracketArraySyntax', get_class($configWriter));
    }

    public function testClassNameScalarsFlagIsSet(): void
    {
        $this->container->expects($this->atLeastOnce())->method('has')->with('config')->willReturn(true);
        $this->container->expects($this->atLeastOnce())->method('get')->with('config')->willReturn([
            'api-tools-configuration' => [
                'class_name_scalars' => true,
            ],
        ]);

        $factory = $this->factory;

        /** @var PhpArray $configWriter */
        $configWriter = $factory($this->container);

        $this->assertTrue($configWriter->getUseClassNameScalars());
    }
}
