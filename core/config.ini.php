<?php
/**
 * Root Path
 */

define( "LibPath", BasePath."/libraries");

/**
 * Program Access Control Flag
 */
define('CZCool',true);

/* SET THE TIME ZONE ===========================
 | China: Asia/Hong_Kong
 | Australia: Australia/Adelaide
* =============================================*/
date_default_timezone_set ("Asia/Hong_Kong");

/* Details Of DB =============================*/
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Abcd123$');
define('DB_INSTANCE_NAME', 'yhuigo');

$config = array();
/**
 * Table Prefix
 */
$config['tb_pref'] = 'yhg_';

$config['log_path'] ='log';

$config['log_salt'] ='frbz';
/**
 * System Configuration - Used in MVC framework
 * 
 */
$config['system'] = array(
		
	//	'ctrler_cls_prefix' 							=>				'cvz'					,   // the controller class's prefix CVZ
		'ctrler_cls_suffix' 							=> 				'Controller'		,   // the controller class suffix
		'ctrler_default' 								=> 				'home'				, //default controller if not defined
		'model_cls_suffix' 						        => 				'Model'			,  // the model class's suffix
		'view_cls_suffix' 							    => 				'View'				,  // the view class's suffix
		'action_suffix' 								=> 				'Op'					, //default suffix of the action
		'action_default' 								=> 				'index' 			 ,//default action if not defined
        'admin_path' 								        => 				'admin/' 			 //default action if not defined
);

$config['tpl'] = array(
		
		'adm_template_path' => 'default',  // the template path for admin
		
		/*
		 * the template path for client
		 */ 
		'clt_template_path' => 'default'  ,	
		
		/*
		 * the template path for mobile device
		*/
		'mb_template_path' => 'mobile'
);

$config['prog_flag'] = array(
		
	'admin' => 'adm',
	'web' => 'web'

);

