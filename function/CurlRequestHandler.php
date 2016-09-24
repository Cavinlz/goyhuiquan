<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CurlRequestHandler {
	
	protected $curl = null;
	protected $target = null;
	protected $timeout = 5;
	/**
	 */
	function __construct($url = '') 
	{
		$this -> target = $url;
	}
	
	
	public function do_curl_get_request()
	{
		if(empty($this -> target)) return false;
		
		$this -> curl = curl_init();
		curl_setopt($this -> curl, CURLOPT_URL, $this -> target);
		curl_setopt($this -> curl, CURLOPT_HEADER, false);

		curl_setopt($this -> curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this -> curl, CURLOPT_TIMEOUT, $this -> timeout);
		
		if ( strlen( $this -> target ) > 5 && strtolower( substr( $this -> target , 0 , 5 ) ) == 'https' )
		{
			curl_setopt($this -> curl, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($this -> curl, CURLOPT_SSLVERSION, CURLOPT_SSLVERSION_TLSVv1);
			curl_setopt($this -> curl, CURLOPT_SSL_VERIFYPEER, false);
		}
		
		
		return curl_exec($this -> curl);
	}
	
	public  function do_curl_post_request($fields)
	{
	    //curl模拟POST的时候，POST的参数应该是以数组的形式传参
	    //if(!is_array($fields)) return false;
	    
	    $this -> curl = curl_init();
	    curl_setopt($this -> curl, CURLOPT_URL, $this -> target);
	    curl_setopt($this->curl, CURLOPT_POST, 1);
	    curl_setopt($this -> curl, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($this -> curl, CURLOPT_RETURNTRANSFER, 1);
	    //curl_setopt($this -> curl, CURLOPT_HEADER, 1);
	    return curl_exec($this -> curl);
	}
	
	
	public function set_target_url($url)
	{
		$this-> target = $url;
		return $this;
	}
	
	public function do_close()
	{
		curl_close($this -> curl);
	}
	
	function __destruct()
	{
		curl_close($this -> curl);
	}
	

}

?>