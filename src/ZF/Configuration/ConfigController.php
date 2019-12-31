<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

class ConfigController extends AbstractConfigController
{
    protected $config;

    public function __construct(ConfigResource $config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
