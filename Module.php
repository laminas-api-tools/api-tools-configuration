<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\Config\Writer\PhpArray;

/**
 * Laminas module
 */
class Module
{
    /**
     * Retrieve autoloader configuration
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array('Laminas\Loader\StandardAutoloader' => array('namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/',
        )));
    }

    /**
     * Retrieve module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Laminas\ApiTools\Configuration\ConfigWriter' => function ($services) {
                $useShortArray = false;
                if ($services->has('Config')) {
                    $config = $services->get('Config');
                    if (isset($config['api-tools-configuration']['enable_short_array'])) {
                        $useShortArray = (bool) $config['api-tools-configuration']['enable_short_array'];
                    }
                }

                $writer = new PhpArray();
                if ($useShortArray && version_compare(PHP_VERSION, '5.4.0', '>=')) {
                    $writer->setUseBracketArraySyntax(true);
                }

                return $writer;
            },
            'Laminas\ApiTools\Configuration\ConfigResource' => function ($services) {
                $config = array();
                $file   = 'config/autoload/development.php';
                if ($services->has('Config')) {
                    $config = $services->get('Config');
                    if (isset($config['api-tools-configuration'])
                        && isset($config['api-tools-configuration']['config_file'])
                    ) {
                        $file = $config['api-tools-configuration']['config_file'];
                    }
                }

                $writer = $services->get('Laminas\ApiTools\Configuration\ConfigWriter');

                return new ConfigResource($config, $file, $writer);
            },
            'Laminas\ApiTools\Configuration\ConfigResourceFactory' => function ($services) {
                $modules = $services->get('Laminas\ApiTools\Configuration\ModuleUtils');
                $writer  = $services->get('Laminas\ApiTools\Configuration\ConfigWriter');

                return new ResourceFactory($modules, $writer);
            },
            'Laminas\ApiTools\Configuration\ModuleUtils' => function ($services) {
                $modules = $services->get('ModuleManager');
                return new ModuleUtils($modules);
            },
        ));
    }
}
