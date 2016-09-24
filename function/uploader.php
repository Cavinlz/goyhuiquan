<?php

/** 
 * @author Cavinlz
 * 
 */
class Uploader {
	
	protected $temp_path = null;
	protected $target_path = null;
	protected $uploaded_file_name = null;
	protected $return_code = GENERAL_SUCCESS_RETURN_CODE;
	protected $err_msg = null;
	
	/**
	 */
	function __construct($target_path = '') 
	{
		$this -> temp_path = BasePath.DS.'resources'.DS.'upload_tmp';
		$this -> target_path = (empty($target_path)) ? BasePath.DS.C('resource.gallery_path') : $target_path;
	}
	
	/**
	 * @tutorial Handling the pics upload
	 *
	 * @version 1.0
	 *
	 * @since Feb 23 , 2015
	 */
	public function upload()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	
	
		// Support CORS
		// header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit; // finish preflight CORS requests here
		}
	
	    
		if ( !empty($_REQUEST[ 'debug' ]) ) {
			$random = rand(0, intval($_REQUEST[ 'debug' ]) );
			if ( $random === 0 ) {
				header("HTTP/1.0 500 Internal Server Error");
				exit;
			}
		}
		
		// header("HTTP/1.0 500 Internal Server Error");
		// exit;
	
	
		// 5 minutes execution time
		@set_time_limit(5 * 60);
	
		// Uncomment this one to fake upload time
		usleep(5000);
	
		$date = date('Ymd');
	
		// Settings
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = $this -> temp_path;
		$uploadDir = $this -> target_path.DS.$date;
		//logg::write($uploadDir);
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
	
	
		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
	
		// Create target dir
		if (!file_exists($uploadDir)) {
			@mkdir($uploadDir);
		}
	
		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			//to generate the unique id to used as file name
			//$fileName = uniqid("pic_");
			$this -> error_logg(104, 'The file has been refused by the system.');
			return;
		}
		
		
		$fileType = explode('.', $fileName);
		$fileName = uniqid("pic_").'.'.end($fileType);
	
		$md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$md5File = $md5File ? $md5File : array();
	
		if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
			die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
		}
	
		$filePath = $targetDir . DS . $fileName;
		$uploadPath = $uploadDir . DS . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
	
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				$this -> error_logg(100, 'Failed to open temp directory.');
				return;
			}
	
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DS . $file;
	
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
					continue;
				}
	
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}
	
	
		// Open temp file
		//to use "b" to enforce to use the binary mode to read/write
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
			$this -> error_logg(102, "Failed to open output stream {$filePath}_{$chunk}.parttmp.");
			return;
			
		}
	
		if (!empty($_FILES)) {
			//is_uploaded_file() to judge whether uploaded via HTTP POST
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				$this -> error_logg(103, "Failed to move uploaded file.");
				return;
			}
	           logg::system('file size:'.($_FILES["file"]["size"]).' mb');
	           logg::system('file size:'.($_FILES["file"]["tmp_name"]).' mb');
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				$this -> error_logg(101, "Failed to open input stream (temp name file ).");
				return;
			}
		} else {
			if (!$in = @fopen("php://input", "rb")) {
				$this -> error_logg(101, "Failed to open input stream (temp name file ).");
				return;
			}
		}
		//fread() to read the binary files
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
	
		@fclose($out);
		@fclose($in);
	
		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
	
		$index = 0;
		$done = true;
		for( $index = 0; $index < $chunks; $index++ ) {
			if ( !file_exists("{$filePath}_{$index}.part") ) {
				$done = false;
				break;
			}
		}
		if ( $done ) {
		    logg::system('is done ?'.$done);
			//'w' if file does not exist then create one
			if (!$out = @fopen($uploadPath, "wb")) {
				$this -> error_logg(102, "Failed to open output stream {$uploadPath}. ).");
				return;
			}
	
			//flock() to lock or release
			if ( flock($out, LOCK_EX) ) {
				for( $index = 0; $index < $chunks; $index++ ) {
					//try to open then read the partical files
					if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
						break;
					}
	
					while ($buff = fread($in, 4096)) {
						fwrite($out, $buff);
					}
	
					@fclose($in);
					//after read , then remove the temp file
					@unlink("{$filePath}_{$index}.part");
				}
				//release the file after append the new content
				flock($out, LOCK_UN);
			}
			@fclose($out);
				
			$this -> uploaded_file_name = $fileName;
	
		}

	}
	
	
	protected function error_logg($code, $msg)
	{
		logg::system("[Code:{$code}]{$msg}");
		$this ->err_msg = $msg;
		$this -> return_code = $code;
	}
	
	public function get_code()
	{
		return $this -> return_code;
	}
	
	public function get_err_msg()
	{
		return $this -> err_msg;
	}
	
	public function get_last_upd_file_name()
	{
		return $this -> uploaded_file_name;
	}
}

?>