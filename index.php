<?php
/**
 * Entry Profile
 *
 *
 * @copyright  Copyright (c) 2015-2016
 * @since      File available since Release v1.0
 * 
 * @author	   Cavin Zhang (mailto:zliang_148@hotmail.com)
 */
session_start();
error_reporting(E_ALL || ~E_NOTICE);
ini_set('display_errors', 'On');
header('Content-Type:text/html;charset=UTF-8');

//error_reporting(E_ALL &amp; ~(E_STRICT | E_NOTICE));
/**
 * Marked Program StratTime
*/
define('StartTime',microtime(true));

define('DS','/');
define('BasePath',str_replace('\\','/',dirname(__FILE__)));
define('CorePath',BasePath.DS.'core');


require(CorePath.DS.'config.ini.php');
require(CorePath.DS.'runtime.php');
require(CorePath.DS.'cvzapp.php');

