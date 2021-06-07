<?php

namespace LaminasTest\ApiTools\Configuration\TestAsset;

use Laminas\Config\Writer\PhpArray as BaseWriter;

class ConfigWriter extends BaseWriter
{
    /** @var string */
    public $writtenFilename;

    /** @var array */
    public $writtenConfig;

    /**
     * @param string $filename
     * @param array $config
     * @param bool $exclusiveLock
     * @return void
     */
    public function toFile($filename, $config, $exclusiveLock = true)
    {
        $this->writtenFilename = $filename;
        $this->writtenConfig   = $config;
    }
}
