<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));

define('GENERAL_SUCCESS_RETURN_CODE',200);
define('GENERAL_ERROR_RETURN_CODE',201);
define('GENERAL_WARNING_RETURN_CODE',202);
define('PERMISSION_DENIED_RETURN_CODE',500);

define('RECORD_NOT_FOUND',400100);

$errorCode = array(
		
		'login_invalid'			=>				array(
		
				'header'			    =>		'Opps!!!'																									,
				'message'			=>		'Sorry, The Account Or Password Is Invalid.'
		
		)																																								,
		
		'404'							=>				array(
		
				'header'				=>		'Opps!!! It is 404!'																								,
				'message'			=>		'Sorry, I am extremely lost now.. ||_||'
		
		)																																								,
		
);

global $errorCode;
