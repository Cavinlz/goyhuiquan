<?php
/*
 * the method to name a pic may follows:
 * Resource One -> time gap 
 * thumbnail -> R_resouce name
 */
class imgoperator{
	var $FilePath = '../temp-upload/logo/'; //temp upload folder
	var $FileSize = 3145728; //equal to 3M
	var $ReSizeImg = array(true,400,400,""); // the 1st parameter:whether need to create the thumbnail
	var $ReSizeResImg = array(true,400,"",""); // the 1st parameter:whether need to create the thumbnail
	var $India = false; //determine whether we need the wartermark
    var $IndiaText = "http://frozenbeerco.com.au"; //the wartermark characters
    var $FontSize = 6; //the size of the characters [from 1~6]
    var $IndiaTextX = 10; //the distance between the left margin to the character
    var $IndiaTextY = 10; //the distance between the top margin to the character
    var $R = 250; //the pic color $Red
    var $G = 250; //$Green
    var $B = 250; //$Blue
    var $IndiaPath = null; //the wartermarked pic location, if it is blank then it will cover the resoucre pic
 	protected $imgFileName;
 	
 	
 	protected $img_setting;
 	
 	/**
	 * {$responseText} contains the final result 's text which is to display after the excel handling
	 */
	public $responseText;
	/**
	 * {$errorReturnCode} is the return code after the handling, 200 for success while 201 for error encountered
	 */
	public $errorReturnCode = GENERAL_SUCCESS_RETURN_CODE;
 	
 	/*
 	 * Return message:
 	 * 1->Successful|2->Size too big|3->the extenstion is invalid
 	 * 4->The Resource Pic doesnot exist
 	 */
 	
 	function __construct($configs = array())
 	{
 		$this -> img_setting = $configs;
 		$this->FilePath = $this -> img_setting['path'];
 	}
 	
	function UploadFile($Upfile)
	{
		
		if(empty($this -> FilePath))
		{
			self::loggingErrorMsgCode( 'Failed to located the storage path !',GENERAL_ERROR_RETURN_CODE);
			return false;
		}
		
		if(!@file_exists($this->FilePath))
		{
			mkdir($this->FilePath);
		}
		/*
		if($this -> India){
			
			if(!@file_exists($this->IndiaPath)){
				mkdir($this->IndiaPath);
			}
			
		}
		*/
		//print_r($Upfile);
		$UpFileType = $Upfile['type']; 
		$UpFileSize = $Upfile['size'];
		$UpFileTmpName = $Upfile['tmp_name'];
		$UpFileName = $Upfile['name'];
		$UpFileError = $Upfile['error'];
		
		/*
		 * To check the file size
		 */
		if($UpFileSize > $this -> FileSize){
				self::loggingErrorMsgCode( '抱歉，暂不支持上传大小超过3M的图片!',GENERAL_ERROR_RETURN_CODE);
				return false;
		}
		/*
		 * To check the file extension
		 */
		switch($UpFileType)
		{
			case "image/JPG":
			case "image/JPEG":
			case "image/jpeg":
				$type = "jpg";
				break;
			case "image/PJPEG":
			case "image/pjpeg":
				$type = "jpg";
				break;
			case "image/PNG":
			case "image/png":
				$type = "png";
				break;
			case "image/GIF":
			case "image/gif":
				$type = "gif";
				break;
		} 
		
		if(!isset($type)){
			self::loggingErrorMsgCode( '抱歉，暂不支持除jpg/png/gif外的其他图片!',GENERAL_ERROR_RETURN_CODE);
			return false;
		}
	
			$FileName = date("YmdHis",time()+3600*8);
			$this->imgFileName = $FileName.".".$type;
			$FileName = $this->FilePath.DS.$this->imgFileName;
			if(!@move_uploaded_file($UpFileTmpName,$FileName)){
				self::loggingErrorMsgCode( '抱歉，找不到原文件!',GENERAL_ERROR_RETURN_CODE);
				return false;
			}else{
				if($this->India)
				{
					$this -> GoIndia($FileName,$type,true);
				}
				else
				{
					if($this -> ReSizeImg[0] == true)
						$this -> GoReImageSize($FileName,$type);
					
					@unlink($FileName);	 //remove the temparory file
					if($this -> errorReturnCode == GENERAL_SUCCESS_RETURN_CODE){
						self::loggingErrorMsgCode('图片上传成功!');
						return true;
					}
					
					return false;
					//unlink($UpFileTmpName);
				}	
			}
	
	}
	
	
	function GoIndia($FileName,$FileType,$ReImage=false)
	{
		switch($FileType)
		{
			case "jpg":
				$img = imagecreatefromjpeg($FileName);
				break;
			case "gif":
				$img = imagecreatefromgif($FileName);
				break;
			case "png":
				$img = imagecreatefrompng($FileName);
				break;
		}
		if($this -> IndiaText != "")
		{
			$TextColor = imagecolorallocate($img,$this->R,$this->G,$this->B);
			//write the text onto the picture
			imagestring($img, $this->FontSize, $this->IndiaTextX, $this->IndiaTextY, $this->IndiaText, $TextColor); 
		}
		
		if($this->IndiaPath =="")
		{
			switch($FileType)
			{
				case "jpg":
					imagejpeg($img,$FileName);
					break;
				case "gif":
					imagegif($img,$FileName);
					break;
				case "png":
					imagepng($img,$FileName);
					break;
			}
			if($ReImage[0] == true)
			{
				$this -> GoReImageSize($FileName,$FileType);
			}
			else{
				return true;
			}
		}
		else
		{
			//errorEventLog::errLogToFile('IndiaPath',$this->IndiaPath);
			if(!@file_exists($this->IndiaPath))
			{	
 				mkdir($this->IndiaPath);
				return false;
			}else{
				$IndiaName = basename($FileName);
				$IndiaName = $this->IndiaPath . $IndiaName;
				switch($FileType)
				{
					case "jpg":
						imagejpeg($img,$FileName);
					break;
					case "gif":
						imagegif($img,$FileName);
					break;
					case "png":
						 imagepng($img,$FileName);
					break;
				}
				if($this->ReSizeImg[0])
				{
					$this -> GoReImageSize($FileName,$FileType);
				}
				else{
					return true;
				}
			}
		}
	}
	
	function GoReImageSize($FileName,$FileType)
	{
		if (!@file_exists($FileName)) {
				self::loggingErrorMsgCode( '抱歉，找不到要生成缩略图的原图片!',GENERAL_ERROR_RETURN_CODE);
				return;
        } else {
            if ($FileType == 'jpg') {
                $ReImage = imagecreatefromjpeg($FileName);
            }
            elseif ($FileType == 'png') {
                $ReImage = imagecreatefrompng($FileName);
            } elseif ($FileType == 'gif') {
                    $ReImage = imagecreatefromgif($FileName);
                }
            if (isset ($ReImage)) {

                $SrcImageType = getimagesize($FileName); //print_r($SrcImageType);
                $SrcImageTypeW = $SrcImageType[0]; //height of the original pic
                $SrcImageTypeH = $SrcImageType[1]; //width of the original pic

                $newWidth = $this -> ReSizeImg[1];
			    $defHeight = $this -> ReSizeImg[2];
			    if($SrcImageTypeW > $this -> ReSizeResImg[1])
			    	$newOriginalWidth = $this -> ReSizeResImg[1];
			    else
			    	$newOriginalWidth = $SrcImageTypeW;
                
			    /*
			     * regenerate the h/w according to the exiting ratio
			     */
			    $newHeight = $SrcImageTypeH * ($newWidth/$SrcImageTypeW);
			    if($newHeight > $defHeight){
			    	$newWidth = $newWidth * ($defHeight/$newHeight);
			    	$newHeight = $defHeight;
			    }
			    $newOriginalHeight = $SrcImageTypeH * ($newOriginalWidth/$SrcImageTypeW);
			    
			  //  if($newWidth > $this -> ReSizeImg[1])
			    //	$newWidth = $this -> ReSizeImg[1];
			    $ReIm = imagecreatetruecolor($newWidth, $newHeight);
			    
			    $ReOriIm = imagecreatetruecolor($newOriginalWidth, $newOriginalHeight);
			        // $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
			       //  imagecopyresampled($resizedImage, $original, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                @imagecopyresampled($ReIm, $ReImage, 0, 0, 0, 0, $newWidth,$newHeight, $SrcImageTypeW, $SrcImageTypeH);
                
                 @imagecopyresampled($ReOriIm, $ReImage, 0, 0, 0, 0, $newOriginalWidth,$newOriginalHeight, $SrcImageTypeW, $SrcImageTypeH);
                $ReImagePath = $this->ReSizeImg[3];
                
                if ($ReImagePath != "") { 
                    if (!@file_exists($ReImagePath)) {
                        mkdir($ReImagePath);
                    } else {
                    	
                        $ReImageName = basename($FileName);
                        $ReImageName = $ReImagePath . "R_" . $ReImageName;
                        if ($FileType == "gif")
                            imagegif($ReIm, $ReImageName);
                        elseif ($FileType == "jpg")
                                imagejpeg($ReIm, $ReImageName);
                            elseif ($FileType == "png")
                                    imagepng($ReIm, $ReImageName);
                        return true;
                    }
                } else {
                	$FileName = basename($FileName);
                	$this -> imgFileName = $this->img_setting['name_pref'] . $FileName;
                	
                    $orgiFileName = $this->FilePath.DS.$FileName;
                    if($this->IndiaPath == ""){
                        $FileName = $this->FilePath.DS. $this->img_setting['name_pref'] . $FileName;
                    }else{
                        $FileName = $this->IndiaPath."R_" . $FileName;
                    }
                    if ($FileType == "gif")
                    {
                        imagegif($ReIm, $FileName);
                        imagegif($ReOriIm,$orgiFileName);
                    }
                    elseif ($FileType == "jpg")
                    {
                    	imagejpeg($ReIm, $FileName);
                    	imagejpeg($ReOriIm,$orgiFileName);
                    }      
                    elseif ($FileType == "png")
                    {
                    	imagepng($ReIm, $FileName);
                    	imagepng($ReOriIm,$orgiFileName);
                    }
                    
                    return true;
                }
            }
        }
    }
    
    /**
     * #loggingErrorMsgCode()#
     * Desp: configure the logging after each operation
     * Params: {$responseMsg} is the response message
     * 				{$errorCode} is the return code (200 is by default which is the successfully code, while 201 is the error code)
     */
    protected function loggingErrorMsgCode($responseMsg, $errorCode = GENERAL_SUCCESS_RETURN_CODE)
    {
    	$this -> responseText = $responseMsg;
    	$this -> errorReturnCode  = $errorCode;
    	if($errorCode == GENERAL_ERROR_RETURN_CODE)
    	{
    		//if encountering any unexpected error , will mark down the error on the event log
    		//$msg = 'Extracting the file ['.$this -> currHandlingFileName.'] encountered some errors ..';
    		//errorEventLog::errLogToFile('OPER_EXCEP', $msg);
    	}
    }
    
    public function get_uploaded_file_name()
    {
    	return $this -> imgFileName;
    }


}