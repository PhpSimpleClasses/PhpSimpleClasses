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


    /**
     * Load a script
     * @param string $path Path of script without extension (e.g.: Views/index)
     * @param array $data Array to be extracted to variables that can be used on loaded script
     */
    public function load(string $path, array $data = [])
    {
        if (defined('ERR_LOG') && ERR_LOG) return;
        if ($data) extract($data);
        require(SOURCEPATH . str_replace('/', DS, $path) . '.php');
    }

    /**
     * Print a JSON from array and ends the program
     * @param array $data Array to be converted in JSON
     */
    public function json(array $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}
