<?php

namespace _core;

class pscInit
{

    public function __construct()
    {
        new ErrorHandler;
        $xuri = explode(BASEURL, @$_SERVER['REQUEST_URI']);
        $xuri[0] = '';
        $uri = @$_SERVER['REQUEST_URI'] ? implode('/', $xuri) : null;
        $uri = explode('?', $uri)[0];
        if ($uri) {
            define('CLI', false);
            new RouteMng($uri);
        } else {
            define('CLI', true);
        }
    }
}

class PSC
{

    public $db;
    public $http;

    public function __construct()
    {
        $this->db = new DB;
        $this->http = new HTTP;
    }
    public function load($path, $data = [])
    {
        if (defined('ERR_LOG') && ERR_LOG) return;
        if ($data) extract($data);
        require(SOURCEPATH . str_replace('/', DS, $path) . '.php');
    }
    public function json($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}
