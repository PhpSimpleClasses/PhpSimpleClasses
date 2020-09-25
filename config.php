<?php

/**
 * PHP Simple Classes
 * Version: 1.0
 * Author: Zimaldo Junior
 * 
 * config.php:
 * Initial settings that are included when running a page. 
 * It can be used to make customized global settings.
 */

//DEFAULT====================
define('DS', DIRECTORY_SEPARATOR);
define('BASEPATH', realpath(__DIR__) . DS);
define('SOURCEPATH', BASEPATH . 'src' . DS);

$_baseurl = explode('/', $_SERVER['REQUEST_URI'])[1] ?? '';
define('BASEURL', '/' . $_baseurl . ($_baseurl ? '/' : ''));
//===========================

//DB====================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', '');
//======================

//ERRORS====================
define('IGNORE_NOTICE', TRUE);
define('IGNORE_WARNIG', FALSE);
//==========================
