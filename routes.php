<?php

/**
 * routes.php
 * URL => Class\Function
 * The route class/function is loaded from "src" directory.
 * Use "$" as a wildcard to pass parameters from the URL to the function.
 */


$routes = [

    '/' => 'example/exampleFunction',
    "/testparams/$/$" => 'example/paramsFunc'

];

$routes['/api'] = 'example/api';
$routes['/testparams/$/other/abc/$'] = 'example/paramsFunc2';
