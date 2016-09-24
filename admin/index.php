<?php
/**
 * Admin Entry Profile
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
if(empty($_REQUEST['media']))
header('Content-Type:text/html;charset=UTF-8');

//error_reporting(E_ALL);
/**
 * Marked Program StratTime
*/
define('StartTime',microtime(true));

define('DS','/');
define('AdmBasePath',str_replace('\\','/',dirname(__FILE__)));
define('BasePath',str_replace('\\','/',dirname(dirname(__FILE__))));
define('CorePath',BasePath.DS.'core');

define('AdmCorePath',AdmBasePath.DS.'core');

require(CorePath.DS.'config.ini.php');
require(CorePath.DS.'runtime.php');
require(AdmCorePath.DS.'cvzapp.php');


