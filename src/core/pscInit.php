<?php

namespace core;

use ErrorException;
use Exception;
use \PDO;

class pscInit
{

    public function __construct($request)
    {
        new ErrorHandler;
        new RouteMng($request);
    }
}

class PSC
{

    public function __construct()
    {
        $this->db = new DB;
    }
}
