<?php

/**
 * autoload.php:
 * Load initial config
 * Regists class loader and runs the framework initializer
 */


require_once 'config.php';

if (ENVIRONMENT == 'production') {
    ini_set('display_errors', FALSE);
}

spl_autoload_register(function ($class) {
    $classPath = str_replace("\\", DS, $class);
    if (explode("\\", $class)[0] == '_core') return include_once(BASEPATH . "$classPath.php");
    return include_once(SOURCEPATH . "$classPath.php");
});

new _core\pscInit();
