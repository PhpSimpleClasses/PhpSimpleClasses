<?php

/**
 * URL => Class\Function
 * A classe das rotas é carregada do diretório "src".
 * Use "$" as a wildcard to pass parameters from the URL to the function.
 */


$routes = [

    '/' => 'example/exampleFunction',
    "/testparams/$/$" => 'example/paramsFunc',
    '/testparams/$/other/abc/$' => 'example/paramsFunc2'

];
