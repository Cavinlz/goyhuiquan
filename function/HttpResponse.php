<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
final class HttpResponse {
	public static $Http_Return_Code = GENERAL_ERROR_RETURN_CODE;
	public static $Http_Return_Message;
	
	public static function setHttpResponseStateMsg($returnCode, $returnMsg = '')
	{
		HttpResponse::$Http_Return_Code = $returnCode;
		HttpResponse::$Http_Return_Message = $returnMsg;
	}
	
	public static function prgExceptionOccurred($returnCode,$returmMsg)
	{
		logg::write($returmMsg,$returnCode);
		die($returmMsg.'[ErrorCode:'.$returnCode.']');
	}
	
	
	public static function setOutput($string)
	{
		echo $string;
	}
	
	public static function setJsonOutput(Array $string = array())
	{
		echo json_encode($string);
		exit();
	}
	
	public static function jsPrompt($params,$func='alertmsg',$win="parent")
	{
		if($win == 'parent')
		{
			echo '<script language="javascript">window.parent.'.$func.'("'.implode('","', $params).'");</script>';
		}
	
	}
	
	public static function outputJsonReturn($msg, $code = GENERAL_SUCCESS_RETURN_CODE)
	{
	    self::setJsonOutput(array('code'=>$code, 'msg'=>$msg));
	}
	
	public static function permissionDenied($json = true)
	{
	    logg::write('Permission Denied.');
	    if($json) self::outputJsonReturn('Permission Denied.',GENERAL_ERROR_RETURN_CODE);
	    else die('Permission Denied.');
	}
	
	public static function invalidRequest($code = 1001, $json = true)
	{
	    logg::write('Invalid Request. ['.$code.']');
	    if($json) self::outputJsonReturn('Invalid Request. ['.$code.']',GENERAL_ERROR_RETURN_CODE);
	    else die('Invalid Request. ['.$code.']');
	}
	
}

