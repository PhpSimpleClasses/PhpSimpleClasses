<?php

/**
 * PHP Simple Classes
 * Version: 1.0
 * Author: Zimaldo Junior
 * 
 * public/index.php:
 * Declares the class loader and starts the route manager,
 * with the route of the current request.
 */

require_once '../config.php';

spl_autoload_register(function ($class) {
    $class = str_replace("\\", '/', $class);
    return require_once(BASEPATH . "/$class.php");
});

$xroute = explode(BASEURL, $_SERVER['REQUEST_URI']);
$xroute[0] = '';
$route = implode('/', $xroute);
new classes\RouteMng($route);
