<?php
/**
 * 
 */
$config['host'] = array(
	
		'PRD'								=>				'http://www.yhuigo.com/'	,
		//'UAT'								=>				'http://192.168.3.135/yhuigo/',
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
$config['prod'] = true;

/**
 * How many records to display per listing page
 * 
 */
$config['records_per_page'] = 20;

/**
 * Whether memcahced feature got installed
 * 
 */
$config['memcached_enable'] = true;
/**
 * Whether use friendly url
 * 
 */
$config['url_friendly'] = true;

$config['resource'] = array(
        
	   'patching'                         =>        'resources/patches'                                                      ,
       'sql_backup'                     =>          'resources/data'                                                         ,
       'temp_folder'                     =>         'resources/temp_upload'                                                  ,
       'cache_data'                    =>           'resources/cache/data'                                                   ,
       'card_logo_path'                =>           'resources/files/card'  ,
       'brand_logo_path'               =>           'resources/files/brands'

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
		
		'web_title'						=>				'优惠GO后台管理平台'						,
		'app_title'						=>				'优惠GO - 集优惠券领取|分享的便民优惠平台'										,
        //'app_title'						=>				'天虹会员现金券包'										,
		'app_charset' 				=>				'utf8'
		
);

/* Accounts Status =============================*/
define('ACCOUNT_STATUS_NORMAL',1);
define('ACCOUNT_STATUS_BLOCK',0);

require(BasePath."/function/general_func.php" );

/**
 * This is for development purpose while testing the mobile interface
 * Whenever the flag is opened (true) the web will be displayed as in mobile device
 * False otherwise.
 *
 */
define('SET_MOBILE_DEVICE',true);
define('WEXIN_FULL_ACCESS_FLAG',2);

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

