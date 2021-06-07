<?php

namespace Laminas\ApiTools\Configuration;

use Laminas\ModuleManager\ModuleManager;
use ReflectionObject;

use function array_key_exists;
use function dirname;
use function file_exists;
use function in_array;
use function is_dir;
use function preg_match;
use function sprintf;
use function str_replace;
use function strtoupper;
use function substr;

use const PHP_OS;

class ModuleUtils
{
    /** @var array */
    protected $modules = [];

    /** @var array */
    protected $moduleData = [];

    public function __construct(ModuleManager $modules)
    {
        $this->modules = $modules->getLoadedModules();
    }

    /**
     * Retrieve the path to the module
     *
     * @param  string $moduleName
     * @return string
     * @throws Exception\InvalidArgumentException If module does not exist.
     * @throws Exception\RuntimeException If unable to locate module path.
     */
    public function getModulePath($moduleName)
    {
        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->moduleData[$moduleName]['path'])) {
            return $this->moduleData[$moduleName]['path'];
        }

        $this->validateModule($moduleName);

        $this->deriveModuleData($moduleName);
        return $this->moduleData[$moduleName]['path'];
    }

    /**
     * Retrieve the path to the module configuration
     *
     * @param  string $moduleName
     * @return string
     * @throws Exception\InvalidArgumentException If module does not exist.
     * @throws Exception\RuntimeException If unable to locate config path.
     */
    public function getModuleConfigPath($moduleName)
    {
        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->moduleData[$moduleName]['config'])) {
            return $this->moduleData[$moduleName]['config'];
        }

        $this->validateModule($moduleName);

        $this->deriveModuleData($moduleName);
        return $this->moduleData[$moduleName]['config'];
    }

    /**
     * Validate that the module actually exists
     *
     * @throws Exception\InvalidArgumentException If the module does not exist.
     */
    protected function validateModule(string $moduleName): void
    {
        if (! array_key_exists($moduleName, $this->modules)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The module specified, "%s", does not exist; cannot retrieve module data',
                $moduleName
            ));
        }
    }

    /**
     * Derive all module data from module name provided
     */
    protected function deriveModuleData(string $moduleName): void
    {
        $configPath                    = $this->deriveModuleConfig($moduleName);
        $modulePath                    = dirname(dirname($configPath));
        $this->moduleData[$moduleName] = [
            'config' => $configPath,
            'path'   => $modulePath,
        ];
    }

    /**
     * Determines the location of the module configuration file
     *
     * @throws Exception\RuntimeException If unable to find the configuration file.
     */
    protected function deriveModuleConfig(string $moduleName): string
    {
        $moduleClassPath = $this->getModuleClassPath($moduleName);
        $configPath      = $this->recurseTree($moduleClassPath);

        if (false === $configPath) {
            throw new Exception\RuntimeException(sprintf(
                'Unable to determine configuration path for module "%s"',
                $moduleName
            ));
        }

        return $configPath;
    }

    /**
     * Derives the module class's filesystem location
     */
    protected function getModuleClassPath(string $moduleName): string
    {
        $module   = $this->modules[$moduleName];
        $r        = new ReflectionObject($module);
        $fileName = $r->getFileName();
        return dirname($fileName);
    }

    /**
     * Recurse upwards through a tree to find the module configuration file
     *
     * @return false|string
     */
    protected function recurseTree(string $path)
    {
        if (! is_dir($path)) {
            return false;
        }

        if (file_exists($path . '/config/module.config.php')) {
            return $path . '/config/module.config.php';
        }

        if (
            strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'
            && (in_array($path, ['.', '/', '\\\\', '\\'])
                || preg_match('#[a-z]:(\\\\|/{1,2})$#i', $path))
        ) {
            // Don't recurse past the root
            return false;
        }

        return $this->recurseTree(dirname($path));
    }

    /**
     * Normalize the module name
     */
    protected function normalizeModuleName(string $moduleName): string
    {
        return str_replace(['.', '/'], '\\', $moduleName);
    }
}
