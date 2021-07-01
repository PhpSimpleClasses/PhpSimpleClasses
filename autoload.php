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
    if (!file_exists(SOURCEPATH . "$classPath.php")) {
        try {
            return new $class;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    return include_once(SOURCEPATH . "$classPath.php");
});

new _core\pscInit();
