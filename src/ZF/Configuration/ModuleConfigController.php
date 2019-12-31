<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Configuration;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\View\ApiProblemModel;

class ModuleConfigController extends AbstractConfigController
{
    protected $configFactory;

    public function __construct(ResourceFactory $factory)
    {
        $this->configFactory = $factory;
    }

    public function getConfig()
    {
        $module = $this->params()->fromQuery('module', false);
        if (!$module) {
            return new ApiProblemModel(
                new ApiProblem(400, 'Missing module parameter')
            );
        }
        $config = $this->configFactory->factory($module);
        return $config;
    }
} 
