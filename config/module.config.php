<?php

/**
 * @see https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\Config\Writer\WriterInterface as ConfigWriter;
use ZF\Config\Writer\WriterInterface;

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
        // Legacy Zend Framework aliases
        'aliases'   => [
            \ZF\Configuration\ConfigResource::class  => ConfigResource::class,
            \ZF\Configuration\ResourceFactory::class => ResourceFactory::class,
            WriterInterface::class                   => ConfigWriter::class,
            \ZF\Configuration\ModuleUtils::class     => ModuleUtils::class,
        ],
        'factories' => [
            ConfigResource::class  => Factory\ConfigResourceFactory::class,
            ResourceFactory::class => Factory\ResourceFactoryFactory::class,
            ConfigWriter::class    => Factory\ConfigWriterFactory::class,
            ModuleUtils::class     => Factory\ModuleUtilsFactory::class,
        ],
    ],
];
