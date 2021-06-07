<?php

namespace Laminas\ApiTools\Configuration\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigResource;
use Laminas\ApiTools\Configuration\ConfigWriter;

class ConfigResourceFactory
{
    /**
     * Default configuration file to use.
     *
     * @var string
     */
    private $defaultConfigFile = 'config/autoload/development.php';

    /**
     * Create and return a ConfigResource.
     *
     * @return ConfigResource
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $this->fetchConfig($container);

        return new ConfigResource(
            $config,
            $this->discoverConfigFile($config),
            $container->get(ConfigWriter::class)
        );
    }

    /**
     * Fetch configuration from the container, if possible.
     *
     * @return array
     */
    private function fetchConfig(ContainerInterface $container)
    {
        if (! $container->has('config')) {
            return [];
        }

        return $container->get('config');
    }

    /**
     * Discover the configuration file to use.
     *
     * @param array $config
     * @return string
     */
    private function discoverConfigFile(array $config)
    {
        if (! isset($config['api-tools-configuration']['config_file'])) {
            return $this->defaultConfigFile;
        }

        return $config['api-tools-configuration']['config_file'];
    }
}
