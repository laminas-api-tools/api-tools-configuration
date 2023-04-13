<?php

/**
 * @see https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\ApiTools\Configuration\Factory\ConfigResourceFactory;
use Laminas\Config\Writer\WriterInterface;

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
    'service_manager'         => [
        'aliases'   => [
            // Legacy Zend Framework aliases
            \ZF\Configuration\ConfigResource::class        => ConfigResource::class,
            \ZF\Configuration\ConfigResourceFactory::class => ResourceFactory::class,
            \ZF\Configuration\ResourceFactory::class       => ResourceFactory::class,
            \ZF\Configuration\ConfigWriter::class          => ConfigWriter::class,
            \ZF\Configuration\ModuleUtils::class           => ModuleUtils::class,

            // Alias for the stub ConfigWriter class
            // TODO Delete both of these in the next major release
            ConfigWriter::class          => WriterInterface::class,
            ConfigResourceFactory::class => ResourceFactory::class,
        ],
        'factories' => [
            ConfigResource::class  => ConfigResourceFactory::class,
            ResourceFactory::class => Factory\ResourceFactoryFactory::class,
            WriterInterface::class => Factory\ConfigWriterFactory::class,
            ModuleUtils::class     => Factory\ModuleUtilsFactory::class,
        ],
    ],
];
