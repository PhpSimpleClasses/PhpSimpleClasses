<?php

/**
 * PHP Simple Classes
 * Version: 1.2.6
 * 
 * config.php:
 * Initial settings that are included when running a page. 
 * It can be used to make customized global settings.
 */

//DEFAULT====================
define('DS', DIRECTORY_SEPARATOR);
define('BASEPATH', realpath(__DIR__) . DS);
define('SOURCEPATH', BASEPATH . 'src' . DS);
//===========================

//CONTEXT==============
define('BASEURL', '/');
define('ENVIRONMENT', 'production');
//development OR production
//======================

//DB====================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'test');
//======================

//ERRORS================
define('IGNORE_NOTICE', TRUE);
define('IGNORE_WARNING', FALSE);
define('IGNORE_VIEW', TRUE);
//======================
