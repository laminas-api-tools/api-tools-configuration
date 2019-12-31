<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigWriter;
use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Configuration\ResourceFactory;

class ResourceFactoryFactory
{
    /**
     * @param ContainerInterface $container
     * @return ResourceFactory
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ResourceFactory(
            $container->get(ModuleUtils::class),
            $container->get(ConfigWriter::class)
        );
    }
}
