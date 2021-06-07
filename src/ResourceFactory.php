<?php

namespace Laminas\ApiTools\Configuration;

use Laminas\Config\Writer\WriterInterface as ConfigWriter;

use function assert;
use function is_array;
use function str_replace;

class ResourceFactory
{
    /** @var ModuleUtils */
    protected $modules;

    /** @var ConfigWriter */
    protected $writer;

    /** @var ConfigResource[] */
    protected $resources = [];

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

        assert(is_array($config));

        $this->resources[$moduleName] = new ConfigResource($config, $moduleConfigPath, $this->writer);
        return $this->resources[$moduleName];
    }

    /**
     * @param array $config
     * @param string $filePath
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
