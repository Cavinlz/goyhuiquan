<?php
/**
 * 
 */
$config['host'] = array(
	
		'PRD'								=>				'http://www.yhuigo.com'	,
		//'UAT'								=>				'http://192.168.3.134/yhuigo/',
        'UAT'								=>				'http://www.yizhita.net/yhuigo/'
		
);

/**
 * Whether in development mode
 * 
 */
$config['debug'] = true;

/**
 * Whether in Prodution mode
 *
 */
$config['prod'] = false;

/**
 * How many records to display per listing page
 * 
 */
$config['records_per_page'] = 20;

/**
 * Whether use friendly url
 * 
 */
$config['url_friendly'] = true;

$config['resource'] = array(
        
	   'patching'                         =>          'resources/patches'                                                      ,
       'sql_backup'                     =>          'resources/data'                                                            ,
       'cache_data'                    =>              'resources/cache/data'                                                   ,
       'card_logo_path'                =>           'resources/files/card'

);

/**
 * Initial Password while creating a new account
 */
$config['init_pswd'] ='Welcome123';

/**
 * Order Prefix
 *
 */
$config['order_pref'] = 'TSL';

/*
 *  How many words as a basic amount to charge
*/
$config['words'] = 1000;


/**
 * System Cache setting
 * 
 */
 $config['syscache'] = array(
         
         'memcached'    =>  false,
         'redis'        =>  false
 );

/**
 * System Email - Smtp
 * 
 */
$config['mail'] = array(
	
		'smtp_server'					=>				'mail.frozenbeerco.com.au'									,
		'smtp_port'						=>				587														,
		'auth_user'						=>				'liang.fan@frozenbeerco.com.au'					,
		'auth_pswd'					=>				'longtime'											,
		'from'								=>				'frozenbeerco@163.com'					,
		'sitename'						=>				'Universal Translation'						,
);

/**
 * Html General config
 *
 */
$config['html'] = array(
		
		'web_title'						=>				'Frozen Beer - frozenbeerco.com.au'						,
		'app_title'						=>				'Frozen Beer Web System'										,
		'app_charset' 				=>				'utf8'
		
);

/* Accounts Status =============================*/
define('ACCOUNT_STATUS_NORMAL',1);
define('ACCOUNT_STATUS_BLOCK',0);

require(BasePath."/function/general_func.php" );

/**
 * Host domain 
 */
$host = (C('prod')) ? C('host.PRD') : $host = C('host.UAT');

define('Web_Domain',$host);

define('SITE_URL', $host);

define( "DBPRE", C('tb_pref') );

addFunc(array(
	'HttpResponse',
	'general_error_code',
	//'actlog'
));

require (LibPath."/Factory.php");
require( LibPath."/applog.php" );

