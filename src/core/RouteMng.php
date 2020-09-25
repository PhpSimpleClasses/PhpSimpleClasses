<?php

namespace core;

class RouteMng
{
    public function __construct($uri)
    {

        require_once(BASEPATH . 'routes.php');
        foreach ($routes as $route => $function) {
            $match = [];
            $routeRgx = '/^' . str_replace(['$', '/'], ['([^/]+)', '\/'], $route) . '$/';
            preg_match($routeRgx, $uri, $match);
            if ($match) {
                break;
            }
        }
        if ($match) {
            array_splice($match, 0, 1);
            $funcX = explode('/', $function);
            $function = $funcX[count($funcX) - 1];
            array_splice($funcX, count($funcX) - 1, 1);
            $classPath = 'Controllers\\' . implode('\\', $funcX);
            $class = new $classPath;
            call_user_func_array([$class, $function], $match);
        } else {
            http_response_code(404);
        }
    }
}
