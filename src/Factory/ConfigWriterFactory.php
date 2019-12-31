<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Config\Writer\PhpArray;

class ConfigWriterFactory
{
    /**
     * Create and return a PhpArray config writer.
     *
     * @param ContainerInterface $container
     * @return PhpArray
     */
    public function __invoke(ContainerInterface $container)
    {
        $writer = new PhpArray();

        if ($this->discoverConfigFlag($container, 'enable_short_array')) {
            $writer->setUseBracketArraySyntax(true);
        }

        if ($this->discoverConfigFlag($container, 'class_name_scalars')) {
            $writer->setUseClassNameScalars(true);
        }

        return $writer;
    }

    /**
     * Discover the $key flag from configuration, if present.
     *
     * @param ContainerInterface $container
     * @param string $key
     * @return bool
     */
    private function discoverConfigFlag(ContainerInterface $container, $key)
    {
        if (! $container->has('config')) {
            return false;
        }

        $config = $container->get('config');

        if (! isset($config['api-tools-configuration'][$key])) {
            return false;
        }

        return (bool) $config['api-tools-configuration'][$key];
    }
}
