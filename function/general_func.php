<?php
if (defined("CZCool") or die( "Access Denied!" ));

function err($key)
{
	if (strpos($key, ".")) {
		$key = explode(".", $key);
		return $GLOBALS['errorCode'][$key[0]][$key[1]];
	}
	return $GLOBALS['errorCode'][$key];
}
function C($key)
{
	if (strpos($key, ".")) {
		$key = explode(".", $key);
		return $GLOBALS['config'][$key[0]][$key[1]];
	}
	return $GLOBALS['config'][$key];
}

/**
 * Just ease the debug purpose
 * @param unknown $key : key name
 * @return $_POST or $_GET value
 */
function G($key)
{
	switch(C('rq_method'))
	{
		case 'post':
			$val =  $_POST[$key];
			break;
		case 'get':
			$val = $_GET[$key];
			break;
		default:
			$val = null;
	}
	return $val;
}

function datetime()
{
	return date('Y-m-d H:i:s');
}
function addFunc($func, $path = '/function')
{
	if(is_array($func)){
		foreach ($func as $val){
			require_once  BasePath.$path.DS.$val.'.php';
		}
	}
	else
		require_once  BasePath.$path.DS.$func.'.php';
}
/**
 * For those must have a default value
 * 
 * @param unknown $chk
 * @param number $default
 * 
 * @since  Nov 11, 2014
 */
function setValue($chk,$default=0)
{
	return (empty($chk)) ? $default : $chk;
}

function formatString($str, $minlen=6, $char = '0')
{
	while(strlen($str)<$minlen){
		$str = $char.$str;
	}
	return $str;
}
function remainTwoDigits($price)
{
	return sprintf("%.2f", $price);
}

function loc_url($url,$showMsgDetails=FALSE, $msgType = '3')
{
	/*
	 * 0 -> no need to redirect url
	* 1 -> back to the previous page and refresh
	* 2 -> back to previous page without refreshing\
	* 3 -> go to the referre url
	*/
	switch($msgType)
	{
		case '0':
			$script ='';
			break;
		case '1':
			$script = "location.href='".$_SERVER['HTTP_REFERER']."'";
			break;
		case '2':
			$script = 'history.back();';
			break;
		case '3':
			$script = "location.href='".$url."'";
			break;
		case '4':
			$script = "window.parent.location.href='".$url."'";
			break;
		default:
			//$msgType = getUtfToBeGbk($msgType);
			$script = 'window.parent.resultSet("'.$msgType.'","'.$showMsgDetails.'","'.$skipPage.'")';
	}

	if($showMsgDetails == FALSE || $msgType > 4)
	{
		echo "<script language='javascript'>".$script."</script>";
	}
	else
	{
		echo "<script language='javascript'>window.alert('".$showMsgDetails."');".$script."</script>";
	}
	//as it is the js code and 'exit' is needed to interrupt the PHP to be running
	exit();
}

/*
 * Used to chinese transmision between ajax and php
* Paras: Utf-8 String
*/
function getUtfToBeGbk($str)
{
	return iconv('UTF-8', 'gbk//IGNORE',$str);
}


function html_mail($from, $to, $subject, $body){

	$headers[] = "From: $from";
	$headers[] = "X-Mailer: PHP";
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/html; charset=utf8";
	$headers[] = "Reply-To: $from";
	$subject = "=?UTF-8?B?".base64_encode($subject)."?=";

	return mail($to, $subject, $body, join("\r\n", $headers), "-f $from");
}


function mailContentTemplate($content)
{
	$body ="<!DOCTYPE html>
			<html>
			<head>
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">
			<title>FrozenBeer Notification</title>
			</head>
			<style>
			*{margin:0px;padding:0px;}
			body{font-family:verdana;font-size:12px;color:#4f5668;}
			#container{width:800px; margin:0px auto;}
			.header{padding:20px 0px; border-bottom:3px solid #277637}
			.footer{padding:50px 0px;border-bottom:3px solid #277637}
			.main{padding-top:50px;font-size:14px;margin:20px;}
			a img{border:0px;}
			</style>
			<body>
			<div id='container'>
				<div class='header'>
					<a href=\"http://frozenbeerco.com\" target=\"_blank\"><img src=\"http://frozenbeerco.com/images/logo-mail.png\"></a>
				</div>
				<div class='main'>".nl2br($content)."</div>
				<div class='footer'>
					<h1 style='font-size:12px; color:#277637;font-style:italic'> (This is system message automatically sent from FrozenBeerco. Please Do Not reply directly.)</h1></br>
				</div>
			</div>
			</body>
			</html>";
	return $body;
}

function get_number_options($start, $end , $valsufix='')
{
	$options = array();
	for($i = $start; $i<=$end; $i++){
		$s = $i>1?'s':'';
		$options[$i] = $i.' '.$valsufix.$s;
	}
	return $options;
}

function format_user_input($str)
{
	return trim(strip_tags($str));
}

function get_date_deduction($later, $former)
{
	$startdate = strtotime($former);
	$enddate = strtotime($later);
	$days=round(($enddate-$startdate)/3600/24) ;
	
	return $days;
}

function get_post_time($later, $former, $return = false)
{
	$startdate = strtotime($former);
	$enddate = strtotime($later);
	
	$time_dedu = $enddate-$startdate;
	
	$days=round($time_dedu/3600/24) ;

	if($days == 0)
	{
		$hours = round(($time_dedu)/3600);
	
		if($hours == 0)
		{
			$mins = round(($time_dedu)/60);

			if($mins == 0){
				$post = $time_dedu.' 秒前';
			}
			else 
				$post = $mins.' 分钟前';
		}
		else 
		{
			$post = $hours.' 小时前';
		}
	}
	elseif($days == 1)
	{
		$post =' 昨天';
	}
	else{
		$post = $days.' 天前';
	} 
	
	if($return) return $post;
	echo $post;
}

function createRandomStr($length = 16)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

function afterSubStringIndex($str, $start){
    $tempStr = '';
    $strLen = $start;
    for ($i = 0; $i < $strLen; $i++)
    {
        if(ord(substr($str, $i, 1)) > 0xa0)
        {
            $i++;
        }

    }
    //echo $start.'&&&'.$i.'</br>';
    return $i;
}
function subString($str, $start = 0, $len = 80)
{
    if (strlen($str) < $len)
    {
        return $str;
    }

    $tempStr = '';
    $strLen = $start + $len;
    //if the start pt we need to identify the `real` started pt
    if($start > 0){
        $start=afterSubStringIndex($str,$start);
    }
    for ($i = $start; $i < $strLen; $i++)
    {
        if(ord(substr($str, $i, 1)) > 0xa0)
        {
            $tempStr .= substr($str, $i, 3); //the chinese in utf8 format contains 3 bytes while in gbk it is 2 bytes
            $i=$i+2;
        }
        else
        {
            $tempStr .= substr($str, $i, 1);
        }
    }
    if(strlen($str) > $len)
        $suffix = '...';
    return $tempStr.$suffix;
}
/*
 * @desc Get system default image storing path
 * 
 */
function get_imgpath($url = true)
{
	return ($url)? Web_Domain.C('resource.gallery_path').DS : BasePath.DS.C('resource.gallery_path').DS;
}

function format_timestamp_onlydate($timestamp)
{
    return date('Y-m-d',$timestamp);
}

//删除空格和回车
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);
}


if(file_exists(BasePath.DS.'function'.DS.'custom_func.php')){
    addFunc('custom_func');
}

if(file_exists(BasePath.DS.'function'.DS.'constants.ini.php')){
    addFunc('constants.ini');
}

