<?php

/**
 * PHP Simple Classes
 * Version: 1.0
 * Author: Zimaldo Junior
 * 
 * public/index.php:
 * Declares the class loader and starts the core,
 * with the uri of the current request.
 */

require_once '../config.php';

spl_autoload_register(function ($class) {
    $class = str_replace("\\", DS, $class);
    return include_once(SOURCEPATH . "$class.php");
});

$xuri = explode(BASEURL, $_SERVER['REQUEST_URI']);
$xuri[0] = '';
$uri = implode('/', $xuri);
new core\pscInit($uri);
