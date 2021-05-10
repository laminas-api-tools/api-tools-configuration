<?php

namespace Laminas\ApiTools\Configuration\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ModuleUtils;

class ModuleUtilsFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModuleUtils
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ModuleUtils($container->get('ModuleManager'));
    }
}
