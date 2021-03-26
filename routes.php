<?php

/**
 * routes.php
 * $routes[] = ['URL', 'Class\Function'] 
 * OR 
 * $routes[] = ['URL', 'Class\Function', 'METHOD']
 * The route class/function is loaded from "src" directory.
 * Use "$" as a wildcard to pass parameters from the URL to the function.
 */


$routes[] = ['/', 'example/exampleFunction'];
$routes[] = ['/testparams/$/$', 'example/paramsFunc'];
$routes[] = ['/methods', 'example/postTest', 'POST'];
$routes[] = ['/methods', 'example/getTest', 'GET'];
$routes[] = ['/api', 'example/api'];
$routes[] = ['/testparams/$/other/abc/$', 'example/paramsFunc2'];
