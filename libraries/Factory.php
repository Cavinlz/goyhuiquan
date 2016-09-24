<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @tutorial This is the globally (Facotry) function to get the global information if exists
 * 
 * @author Cavinlz
 * 
 * @since v1.0
 */
final class CFactory {
	protected static $template_path = null;
	protected static $template_lang = null;
	protected static $header_title = null; 
	protected static $html_charset = null;
	protected static $website_domain = null;
	protected static $application_pos= null;
	protected static $application_stylesheet=array();
	/**
	 * @tutorial Initialize the global configuration from the config file
	 */
	public static function init()
	{
		self::$website_domain = (self::$application_pos) ? SITE_URL.self::$application_pos.DS : SITE_URL;
		
		self::$template_path = self::$website_domain.'templates/'.((defined('IS_MOBILE_DEVICE'))?C('tpl.mb_template_path'):C('tpl.clt_template_path'));
		self::$header_title = C('html.app_title');
		self::$html_charset = C('html.app_charset');
		self::$template_lang = 'en';
		
		/*
		 * Configure to be Autoload requested classes at runtime.
		*
		* @since Mar 16th,2015
		*/
		spl_autoload_register(array('CFactory','libLoader'));
		spl_autoload_register(array('CFactory','controllerLoader'));
		spl_autoload_register(array('CFactory','modelLoader'));
		spl_autoload_register(array('CFactory','viewLoader'));
	} 
	
	public static function setApplicationPosition($pos = '')
	{
		//to tell the current visited application is backend admin position
		self::$application_pos = empty($pos) ?  '' : $pos;
	}
	
	public static function getApplicationPosition()
	{
		return self::$application_pos;
	}
	
	public static function getApplicationCharset()
	{
		return self::$html_charset;
	}
	
	public static function getApplicationSubject()
	{
		return self::$header_title;
	}
	
	/**
	 * @method addApplicationStyleSheet()
	 *
	 * @tutorial Allow to dynamic add the stylesheet at the application
	 *
	 * @param string or array with the stylesheet inside
	 */
	public static function addApplicationStyleSheet($stylesheet)
	{
		if(empty($stylesheet)) return;
		
		if(is_array($stylesheet))
		{
			foreach ($stylesheet as $val)
			{
				array_push(self::$application_stylesheet, $val);
			}
		}
		else
		{
			array_push(self::$application_stylesheet, $stylesheet);
		}
	}
	
	public static function getApplicationStyleSheet()
	{
		return self::$application_stylesheet;
	}
	
	/**
	 * @method getApplicationTemplateURL()
	 * 
	 * @tutorial Allow to get the template path at anywhere from the application
	 * 
	 * @return string(URL format rather than the absolute path)
	 */
	public static function getApplicationTemplateURL()
	{
		return self::$template_path;
	}
	
	public static function getApplicationPositionURL()
	{
		return self::$website_domain;
	}
	
	public static function setApplicationLanguage($lang)
	{
		self::$template_lang = $lang;
	}
	
	public static function getApplicationLanguage()
	{
		return self::$template_lang;
	}
	

	public static function libLoader($class)
	{
		$file = LibPath.DS.$class.".php";
			
		if(is_file($file))
			require_once $file;
	}
	
	public static function controllerLoader($class)
	{
		//remove the suffix of the class
		$class = preg_replace ( '/Controller$/ui', '', $class );
		$file = empty(self::$application_pos)?BasePath.'/controllers/'.$class.".php":AdmBasePath.'/controllers/'.$class.".php";
		if(is_file($file))
			require_once $file;
	}
	
	public static function modelLoader($class)
	{
	    if(defined('AdmBasePath')){
	        $backendfile = AdmBasePath.'/models/'.$class.".php";
	    }else {
	        $backendfile = C('system.admin_path').'/models/'.$class.".php";
	    }
	   
		//$file = empty(self::$application_pos)?BasePath.'/models/'.$class.".php":AdmBasePath.'/models/'.$class.".php";
		
		if(empty(self::$application_pos)){
		   $file = BasePath.'/models/'.$class.".php";
		   
		   if(file_exists($file)){
		       require_once $file;
		   }
		   else{
		       require_once $backendfile;
		   }
		       
		}
		elseif(is_file($backendfile))
			require_once $backendfile;
	}
	
	public static function viewLoader($class)
	{
		$file = empty(self::$application_pos)?BasePath.'/views/'.$class.".php":AdmBasePath.'/views/'.$class.".php";
			
		if(is_file($file))
			require_once $file;
	}
	
	public static function rootControllerLoader($class)
	{
	    $class = preg_replace ( '/Controller$/ui', '', $class );
	    $file = BasePath.'/controllers/'.$class.".php";
	    if(is_file($file))
	        require_once $file;
	}
	
    public static function rootModelLoader($class)
	{
		$file = BasePath.'/models/'.$class.".php";
			
		if(is_file($file))
			require_once $file;
	}
}
