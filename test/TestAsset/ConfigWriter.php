<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Configuration\TestAsset;

use Laminas\Config\Writer\PhpArray as BaseWriter;

class ConfigWriter extends BaseWriter
{
    /** @var string */
    public $writtenFilename;
    /** @var mixed */
    public $writtenConfig;

    /**
     * @param string $filename
     * @param mixed $config
     * @param bool $exclusiveLock
     */
    public function toFile($filename, $config, $exclusiveLock = true)
    {
        $this->writtenFilename = $filename;
        $this->writtenConfig   = $config;
    }
}
