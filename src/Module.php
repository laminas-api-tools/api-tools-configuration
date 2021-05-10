<?php

namespace Laminas\ApiTools\Configuration;

/**
 * Laminas module
 */
class Module
{
    /**
     * Retrieve module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
