<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

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
