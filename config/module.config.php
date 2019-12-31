<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-configuration for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-configuration/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-configuration/blob/master/LICENSE.md New BSD License
 */

return array(
    'api-tools-configuration' => array(
        'config_file' => 'config/autoload/development.php',
        'enable_short_array' => true,
    ),
    'api-tools-api-problem' => array(
        'render_error_controllers' => array(
            'Laminas\ApiTools\Configuration\ConfigController',
        ),
    ),
    'api-tools-content-negotiation' => array(
        'controllers' => array(
            'Laminas\ApiTools\Configuration\ConfigController' => 'Json',
        ),
        'accept-whitelist' => array(
            'Laminas\ApiTools\Configuration\ConfigController' => array(
                'application/json',
                'application/vnd.laminascampus.v1.config+json',
            ),
        ),
        'content-type-whitelist' => array(
            'Laminas\ApiTools\Configuration\ConfigController' => array(
                'application/json',
                'application/vnd.laminascampus.v1.config+json',
            ),
        ),
    ),
);
