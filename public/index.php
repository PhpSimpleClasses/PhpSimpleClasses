<?php

/**
 * public/index.php:
 * Declares the class loader and starts the core,
 * with the uri of the current request.
 */

require_once '../config.php';

if (ENVIRONMENT == 'production') {
    ini_set('display_errors', FALSE);
}

spl_autoload_register(function ($class) {
    $class = str_replace("\\", DS, $class);
    if (explode("\\", $class)[0] == '_core') return include_once(BASEPATH . "$class.php");
    return include_once(SOURCEPATH . "$class.php");
});

$xuri = explode(BASEURL, $_SERVER['REQUEST_URI']);
$xuri[0] = '';
$uri = implode('/', $xuri);
new _core\pscInit($uri);
