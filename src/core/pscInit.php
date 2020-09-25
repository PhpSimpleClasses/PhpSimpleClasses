<?php

namespace core;

use ErrorException;
use Exception;
use \PDO;

class pscInit
{
    private $errH;

    public function __construct($request)
    {
        $this->errH = new ErrorHandler;
        new RouteMng($request);
    }
}

class PSC
{

    public $db;

    public function __construct()
    {
        $this->db = new DB;
    }
}
