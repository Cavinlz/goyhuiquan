<?php
if (defined("CZCool") or die( "Access Denied!" ));

if ( !is_array( $config ) )
{
	exit( "Config params error" );
}

global $config;

define('Controller_Path',BasePath.DS.'controllers');
define('Model_Path',BasePath.DS.'models');
define('View_Path',BasePath.DS.'views');

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";

define('Template_Path',BasePath.DS.'templates'.DS.C('tpl.clt_template_path'));

//check if from wechat browser
if ( strpos($ua, 'micromessenger') !== false ) {
	define('IS_WECHAT_BROWSER',true);
}

require( CorePath.DS."webconsole.php" );

Console::run();

