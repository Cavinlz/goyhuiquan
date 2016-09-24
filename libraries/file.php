<?php
if ( defined( "CVZAccess" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CFile
{

    public static $backup_folder = 'backup';
    /**
     */
    function __construct ()
    {}
    
    /**
     * 删除文件夹
     *
     * @param string $aimDir
     * @return boolean
     */
    public static function unlinkDir($aimDir) 
    {
    	$aimDir = str_replace('', '/', $aimDir);
    	$aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
    	if (!is_dir($aimDir)) {
    		return false;
    	}
    	$dirHandle = opendir($aimDir);
    	while (false !== ($file = readdir($dirHandle))) {
    		if ($file == '.' || $file == '..') {
    			continue;
    		}
    		if (!is_dir($aimDir . $file)) {
    			CFile :: unlinkFile($aimDir . $file);
    		} else {
    			CFile :: unlinkDir($aimDir . $file);
    		}
    	}
    	closedir($dirHandle);
    	return rmdir($aimDir);
    }
    
    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
    public static function unlinkFile($aimUrl) 
    {
    	if (file_exists($aimUrl)) {
    		unlink($aimUrl);
    		//rename($aimUrl, $aimUrl.'_'.date('Ymd'));
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * 复制文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    public static function copyDir($oldDir, $aimDir, $overWrite = true) 
    {
    	$aimDir = str_replace('', '/', $aimDir);
    	$aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
    	$oldDir = str_replace('', '/', $oldDir);
    	$oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
    	if (!is_dir($oldDir)) {
    		return false;
    	}
    	if (!file_exists($aimDir)) {
    		CFile :: createDir($aimDir);
    	}
    	$dirHandle = opendir($oldDir);
    	while (false !== ($file = readdir($dirHandle))) {
    		if ($file == '.' || $file == '..') {
    			continue;
    		}
    		if (!is_dir($oldDir . $file)) {
    			CFile :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
    		} else {
    			CFile :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
    		}
    	}
    	return closedir($dirHandle);
    }
    
    /**
     * 复制文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    public static function copyFile($fileUrl, $aimUrl, $overWrite = true) 
    {
    	if (!file_exists($fileUrl)) {
    		return false;
    	}
    	if (file_exists($aimUrl) && $overWrite == false) {
    		return false;
    	} elseif (file_exists($aimUrl) && $overWrite == true) {
    		CFile :: unlinkFile($aimUrl);
    	}
    	$aimDir = dirname($aimUrl);
    	CFile :: createDir($aimDir);
    	copy($fileUrl, $aimUrl);
    	return true;
    }
    
    /**
     * 建立文件夹
     *
     * @param string $aimUrl
     * @return viod
     */
    public static function createDir($aimUrl) 
    {
    	$aimUrl = str_replace('', '/', $aimUrl);
    	$aimDir = '';
    	$arr = explode('/', $aimUrl);
    	$result = true;
    	foreach ($arr as $str) {
    		$aimDir .= $str . '/';
    		if (!file_exists($aimDir)) {
    			$result = mkdir($aimDir);
    		}
    	}
    	return $result;
    }
}

?>