<?php

namespace Laminas\ApiTools\Configuration\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigWriter;
use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ApiTools\Configuration\ResourceFactory;

class ResourceFactoryFactory
{
    /**
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
