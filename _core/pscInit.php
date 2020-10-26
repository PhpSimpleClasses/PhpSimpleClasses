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

    public function __construct()
    {
        $this->db = new DB;
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
