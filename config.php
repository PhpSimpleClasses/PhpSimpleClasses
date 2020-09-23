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
define('BASEPATH', realpath(__DIR__ . '/../'));

$_baseurl = explode('/', $_SERVER['REQUEST_URI'])[1] ?? '';
define('BASEURL', '/' . $_baseurl . ($_baseurl ? '/' : ''));
//===========================
