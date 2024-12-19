<?php
/**
 * @var \MODX\Revolution\modX $modx
 * @var array $namespace
 */

// Include autoloader generated by your composer
require_once $namespace['path'] . 'vendor/autoload.php';

if (!$modx->services->has('bentohook')) {
    // Register base class in the service container
    $modx->services->add('bentohook', function($c) use ($modx) {
        return new \BentoHook\Service($modx);
    });

    // Load packages model, uncomment if you have DB tables
    //$modx->addPackage('BentoHook\Model', $namespace['path'] . 'src/', null, 'BentoHook\\');
}

// More about this file: https://docs.modx.com/3.x/en/extending-modx/namespaces#bootstrapping-services
