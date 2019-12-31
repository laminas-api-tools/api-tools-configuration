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

        if ($this->discoverEnableShortArrayFlag($container)) {
            $writer->setUseBracketArraySyntax(true);
        }

        return $writer;
    }

    /**
     * Discover the enable_short_array flag from configuration, if present.
     *
     * @param ContainerInterface $container
     * @return bool
     */
    private function discoverEnableShortArrayFlag(ContainerInterface $container)
    {
        if (! $container->has('config')) {
            return false;
        }

        $config = $container->get('config');

        if (! isset($config['api-tools-configuration']['enable_short_array'])) {
            return false;
        }

        return (bool) $config['api-tools-configuration']['enable_short_array'];
    }
}
