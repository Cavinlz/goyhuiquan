<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CMemory {
    
	protected static $cache_storage = array();
	protected static $cache_path = null;
	protected static $mc = null;
	
	
	/**
	 */
	public static function init($caches = '') {
		self::$cache_path = BasePath.DS.'resources'.DS.'cache'.DS;
		
		if(!empty($caches)){
			self::load_caches($caches);
		}
		
		if(C('memcached_enable')){
		    
		    self::$mc = new Memcache();
		    self::$mc -> connect('127.0.0.1',11211);
		    
		}
		
	}
	
	public static function cache_file_exists($file)
	{
		if(file_exists(self::$cache_path.$file.'.php')) return true;
		return false;
	}
	
	protected static function set_cache_storage(Array $lang = array())
	{
	    if(empty(self::$cache_storage))
		      self::$cache_storage = $lang;
	    else 
	           self::$cache_storage = array_merge(self::$cache_storage, $lang);
	}
	
	public static function get($key)
	{
		if (strpos($key, ".")) {
			$key = explode(".", $key);
			return self::$cache_storage[$key[0]][$key[1]];
		}
		return self::$cache_storage[$key];
	}
	
	/**
	 * @tutorial To rebuild a cache file which locates in the CorePath/cache folder
	 * 
	 * @param unknown $filename
	 * @param unknown $var
	 * @param unknown $val
	 */
	public static function build_cache_file($filename, $var, $val, $ignore = array())
	{
	    /**
	     * @tutorial Please note all the cache variables must use the variable $CMemory
	     */
	    $str_tmp  ="<?php\r\n";
	    $str_tmp .="$"."CMemory[\"".$var."\"] = array(\r\n";
	    $str_tmp .= "\r\n";
	    if(is_array($val)){
	    	foreach ($val as $k => $v)
	    	{
	    		if(in_array($k, $ignore)) continue;
	    		
	    		if(is_array($v)){
	    		    $str_tmp .= '"'.$k.'"=> array(';
	    		    foreach($v as $a)
	    		    {
	    		       $str_tmp .= '"'.$a.'",';
	    		    }
	    		    $str_tmp = substr( $str_tmp, 0,-1);
	    		    $str_tmp .= '),';
	    		}
	    		else
	    	      $str_tmp .= '"'.$k.'"=>'.'"'.$v.'",';
	    	    $str_tmp .= "\r\n";
	    	}
	    }
	    $str_tmp .= "\r\n";
	    $str_tmp .= ");";
	    
	    /**
	     * Create folder incase not exist
	     */
	    if(strpos($filename, '/') > 0)
	    {
	        Console::loadSystemLibary('file');
	        $arr = explode('/', $filename);
	        
	        CFile::createDir(self::$cache_path.$arr[0]);
	    }
	    
	    $file = self::$cache_path.$filename.'.php';
	    try{
	        
	        $fp=fopen($file,"w");
	        fwrite($fp,$str_tmp);
	        fclose($fp);
	    
	    }
	    catch(Exception $e){
	        Logg::system($e->getMessage());
	    }
	    
	}
	
	public static function load_caches($cachefiles)
	{
		if(is_array($cachefiles))
		{
			foreach ($cachefiles as $val)
			{
			    $path = self::$cache_path.DS.$val.'.php';
				if(file_exists($path)){
					require_once $path;
				}
			}
			if(!empty($CMemory)){
			    self::set_cache_storage($CMemory);
			}
		}
	}
	
	public static function load_cache_file($filename)
	{
	    $path = self::$cache_path.DS.$filename.'.php';
	    if(file_exists($path)){
	    	require_once $path;
	    }
	    
	    if(!empty($CMemory)){
	    	self::set_cache_storage($CMemory);
	    }
	}
	
	public static function load_config_file($cachefiles)
	{
		$filename = 'configuration'.DS.$cachefiles.'_config';
		self::load_cache_file($filename);
	}
	
	
	public static function mc_get($key)
	{
	   return self::$mc -> get($key);
	}
	
	public static function mc_set($key, $var, $flag = 0, $expire = 3600)
	{
	    self::$mc -> set($key, $var, $flag, $expire);
	}
	
	/**
	 * timeout = 0  means delete the key immediately
	 * @param unknown $key
	 * @param number $timeout
	 */
	public static function mc_del($key ,$timeout = 0)
	{
	    return self::$mc -> delete($key, $timeout);
	}
}

?>