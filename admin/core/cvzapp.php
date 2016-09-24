<?php
if (defined("CZCool") or die( "Access Denied!" ));

if ( !is_array( $config ) )
{
	exit( "Config params error" );
}

global $config;



define('Controller_Path',AdmBasePath.DS.'controllers');
define('Model_Path',AdmBasePath.DS.'models');
define('View_Path',AdmBasePath.DS.'views');

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);


$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";

define('Template_Path',AdmBasePath.DS.'templates'.DS.C('tpl.clt_template_path'));
	
CFactory::setApplicationPosition('admin');

require( AdmCorePath.DS."webconsole.php" );

Console::run();

