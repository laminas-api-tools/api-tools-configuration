<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

return [
    'api-tools-configuration' => [
        'config_file' => 'config/autoload/development.php',
        // set the following flag if you wish to use short array syntax
        // in configuration files manipulated by the ConfigWriter:
        // 'enable_short_array' => true,

        // class_name_scalars defines whether configuration files
        // manipulated by the ConfigWriter should use ::class notation
        // 'class_name_scalars' => true,
    ],
    'service_manager' => [
        // Legacy Zend Framework aliases
        'aliases' => [
            \ZF\Configuration\ConfigResource::class => ConfigResource::class,
            \ZF\Configuration\ConfigResourceFactory::class => ConfigResourceFactory::class,
            \ZF\Configuration\ConfigWriter::class => ConfigWriter::class,
            \ZF\Configuration\ModuleUtils::class => ModuleUtils::class,
        ],
        'factories' => [
            ConfigResource::class        => Factory\ConfigResourceFactory::class,
            ConfigResourceFactory::class => Factory\ResourceFactoryFactory::class,
            ConfigWriter::class          => Factory\ConfigWriterFactory::class,
            ModuleUtils::class           => Factory\ModuleUtilsFactory::class,
        ],
    ],
];
