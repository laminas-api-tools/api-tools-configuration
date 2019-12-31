<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\Config\Writer\WriterInterface as ConfigWriter;

class ResourceFactory
{
    /**
     * @var ModuleUtils
     */
    protected $modules;

    /**
     * @var ConfigWriter
     */
    protected $writer;

    /**
     * @var ConfigResource[]
     */
    protected $resources = [];

    /**
     * @param  ModuleUtils $modules
     * @param  ConfigWriter $writer
     */
    public function __construct(ModuleUtils $modules, ConfigWriter $writer)
    {
        $this->modules = $modules;
        $this->writer  = $writer;
    }

    /**
     * Retrieve a ConfigResource for a given module
     *
     * @param  string $moduleName
     * @return ConfigResource
     */
    public function factory($moduleName)
    {
        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->resources[$moduleName])) {
            return $this->resources[$moduleName];
        }

        $moduleConfigPath = $this->modules->getModuleConfigPath($moduleName);
        $config           = include $moduleConfigPath;

        $this->resources[$moduleName] = new ConfigResource($config, $moduleConfigPath, $this->writer);
        return $this->resources[$moduleName];
    }

    /**
     * @param array $config
     * @param $filePath
     * @return ConfigResource
     */
    public function createConfigResource(array $config, $filePath)
    {
        return new ConfigResource($config, $filePath, $this->writer);
    }

    /**
     * @param string $moduleName
     * @return string
     */
    protected function normalizeModuleName($moduleName)
    {
        return str_replace(['.', '/'], '\\', $moduleName);
    }
}
