<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 * Output the sys logging for debug purpose
 */
final class Logg {
	private static $logFolder;
	private static $Fname='appl.log';
	private static $SysFname='system.log';
	private static $backupDirectory = 'backup';
	private static $logFileLimitSize = 5242880; //5M 5242880
	/**
	 */
	function __construct() {
	}
	
	public static function getRequestURI()
	{
		
			if (isset($_SERVER['argv']))
			{
				$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
			}
			else
			{
				$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
			}
		
			return $uri;
	}
	
	/**
	 * @tutorial The application log is used to recorded the user behavior exception , showed the error code so as to easy for checking
	 *
	 * @param unknown $msg
	 */
	public static function write($msg,$errorCode = '',$filename = '')
	{
			$filename = empty($filename)?Logg::$Fname: $filename.'.log';
			Logg::initFolderChk();
			//clear the cache of the file object ,otherwise the filesize will be stored permanently
			clearstatcache();
			$errorCode = ($errorCode!='')? $errorCode:'INFO';
			//only development uses such logging ,for securing pupose
			$bundleMsg = date("Y-m-d, H:i:s ").'|'.session_id().'|** ' .$errorCode.' ** |'.self::getRequestURI().'|'.$msg." \r\n";
			$fp=fopen(Logg::$logFolder.$filename,'a');
			@fwrite($fp,$bundleMsg);
			@fclose($fp);
	}
	
	/**
	 * @tutorial The system log is used to recorded the program exception
	 * 
	 * @param unknown $msg
	 */
	public static function system($msg)
	{
			Logg::initFolderChk();
			//clear the cache of the file object ,otherwise the filesize will be stored permanently
			clearstatcache();
			$bundleMsg = date("Y-m-d, H:i:s ").'|'.session_id().'|'.self::getRequestURI().'|**** '.$msg." \r\n";
			$fp=fopen(Logg::$logFolder.Logg::$SysFname,'a');
			@fwrite($fp,$bundleMsg);
			@fclose($fp);
	}
	
	public static function initFolderChk()
	{
		if(!Logg::$logFolder)
			Logg::$logFolder = BasePath.DS.C('log_path').DS;
		
		if(!is_dir(Logg::$logFolder)){
			@mkdir(Logg::$logFolder,0777);
		}
		
		if(@file_exists(Logg::$logFolder.Logg::$Fname)){
			if(filesize(Logg::$logFolder.Logg::$Fname) >= Logg::$logFileLimitSize)
			{
				$destDirectory = Logg::$logFolder.DS.Logg::$backupDirectory.DS.date('Ymd').'_appl.bak';
				$moved = @copy(Logg::$logFolder.Logg::$Fname,$destDirectory);
				$remvPath = Logg::$logFolder.Logg::$Fname;
			}
		}
		
		if($moved)
		{
			//delete the old one as it is out of limit
			$del = @unlink($remvPath);
		}
	}
	
    public static function debug($msg)
	{
		$debug = CMemory::get('sys_config.sys_debug');
		
		if($debug){
		    self::write($msg,'DEBUG','appl');
		}
		
	}
}

?>