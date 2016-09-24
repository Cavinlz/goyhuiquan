<?php

class iplimit
{
	protected $path = "ipdata.db";
	protected $iptable = null;
	protected $msg = null;
	protected $code = null;
	
	public function __construct() {
		$content = file_get_contents(dirname(__FILE__).DS.$this->path);
		if(empty($content)) {
			$this->show('1');
			//exit('IP数据库破损');
			logg::write($this ->msg);
			exit;
		}
		eval("\$this->iptable = $content;");
	}

	public function GetIP() {
		if ($ip = getenv('HTTP_CLIENT_IP'));
		elseif ($ip = getenv('HTTP_X_FORWARDED_FOR'));
		elseif ($ip = getenv('HTTP_X_FORWARDED'));
		elseif ($ip = getenv('HTTP_FORWARDED_FOR'));
		elseif ($ip = getenv('HTTP_FORWARDED'));
		else    $ip = $_SERVER['REMOTE_ADDR'];
		return  $ip;
	}

	public function get_ip()
	{
		$unknown = 'unknown';
		if ( isset($_SERVER['HTTP_X_FORWARDED_FOR'])
		&& $_SERVER['HTTP_X_FORWARDED_FOR']
		&& strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],
				$unknown) )
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif ( isset($_SERVER['REMOTE_ADDR'])
				&& $_SERVER['REMOTE_ADDR'] &&
				strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) )
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		/*
		 处理多层代理的情况
		或者使用正则方式：$ip = preg_match("/[d.]
		{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
		*/
		if (false !== strpos($ip, ','))
			$ip = reset(explode(',', $ip));
		//logg::write($ip);
		return $ip;
	}
	
	public function CheckIp($ip = '') {
		!$ip &&$ip = $this->GetIp();
		$ip2 = sprintf('%u',ip2long($ip));
		$tag = reset(explode('.',$ip));
		if(!$ip) {
			$this->show(2);
			return true;
		}
		if('192'== $tag ||'127'== $tag) {
			$this->show(4);
			return false;
		}
		if(!isset($this->iptable[$tag])) {
			$this->show(3);
			return false;
		}
		foreach($this->iptable[$tag] as $k =>$v) {
			if($v[0] <= $ip2 &&$v[1] >= $ip2) {
				$this->show('in');
				return true;
			}
		}
		$this->show('out');
		return false;
	}

	public function show($code) {
		$msg = array(
			1 =>"IP数据库文件破损",
			2 =>"取不到IP地址 可能使用了代理",
			4 =>"在局域网内",
			'out'=>"IP地址在国外",
			'in'=>"IP地址在国内",
		);

		$this->code = $code;
		$this->msg = $msg[$code];
		
	}

	public function is_domestic_ip($ip)
	{
		$ip2 = sprintf('%u',ip2long($ip));
		$array = explode('.',$ip);
		$tag = reset($array);
		if(!$ip) {
			$this->show(2);
			return false;
		}
		if('192'== $tag ||'127'== $tag) {
			$this->show(4);
			return false;
		}
		if(!isset($this->iptable[$tag])) {
			$this->show(3);
			return false;
		}
		foreach($this->iptable[$tag] as $k =>$v) {
			if($v[0] <= $ip2 &&$v[1] >= $ip2) {
				$this->show('in');
				return true;
			}
		}
		$this->show('out');
		return false;
	}
	
	function __destruct() {
		unset($this->iptable);
	}
}
?>