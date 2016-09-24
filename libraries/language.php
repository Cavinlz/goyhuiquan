<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CLanguage {
	
	/**
	 * The default language, used when a language file in the requested language does not exist.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected static $default = 'zh-cn';
	
	/** 
	 * It requires to add the following member $langtb in case there is a need to add extra lang packs
	 * 
	 * @var unknown
	 */
	protected static $langtb = array('zh-cn'=>'cn','en-us'=>'en');
	/**
	 */
	protected static $paths = null;
	
	protected static $lang_storage = array();
	
	public static function init($ctrl, $lang='')
	{
		
		if(CFactory::getApplicationPosition())
			self::$paths = BasePath.'/'.CFactory::getApplicationPosition().'/languages/';
		else
			self::$paths = BasePath.'/languages/';
		
		/**
		 * Set the language folder
		 */
		if(!empty($lang))
		{
			$key = array_search($lang, self::$langtb);
			if($key)
				$dir = self::$paths.$key.DS;
			
			if(!is_dir($dir)){
				self::$paths = self::$paths.self::$default.'/';
			}
			else{
				self::$paths = $dir;
			}
			CFactory::setApplicationLanguage($lang);
		}
		else 
		{
			self::$paths = self::$paths.self::$default.'/';
		}
		
		$language = array();
		
		/**
		 * Include the system default lang package
		 */
		if(file_exists(self::$paths.'common.php'))
			require_once self::$paths.'common.php';
		
		$filename = self::$paths.$ctrl.'.php';
		
		if(file_exists($filename)){
			require_once $filename;
		}
		
		self::set_lang_storage($language);
		
	}
	
	protected static function set_lang_storage(Array $lang = array())
	{
		self::$lang_storage = array_merge(self::$lang_storage,$lang);
	}
	
	public static function Text($key)
	{
		if (strpos($key, ".")) {
			$explkey = explode(".", $key);
			return (self::$lang_storage[$explkey[0]][$explkey[1]])?self::$lang_storage[$explkey[0]][$explkey[1]]:$key;
		}
		return (self::$lang_storage[$key])?self::$lang_storage[$key]:$key;
	}
	
	
	public static function load_lang_file($filename)
	{
		$path = self::$paths.DS.$filename.'.php';
		if(file_exists($path)){
			require_once $path;
		}
		 
		if(!empty($language)){
			self::set_lang_storage($language);
		}
	}
}

?>