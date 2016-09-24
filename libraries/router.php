<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * @since Dec 19, 2014
 * 
 */
final class Router {
	
	public $url_params;
	public $query_string;
	public $route_url =  array();
	public $is_mobile = false;
	
	/**
	 * @tutorial  Get the current visiting url and store it as an array ]
	 * 
	 */
	public function __construct() 
	{
		$this -> url_params = parse_url(Logg::getRequestURI());
		//print_r(Logg::getRequestURI());
	}
	/**
	 * @tutorial Parse the url query string into array ,
	 *  	if array does not exist, then the query string will be specific variable as its key
	 * 
	 * 
	 */
	public function parseUrlString()
	{
		parse_str($this ->url_params['query'],$this ->query_string);
		
		if(count($this ->query_string) > 0)
		{
			if(isset($this -> query_string['ctrl'])) // get the controller
			{
				$this->route_url['controller'] = $this -> query_string['ctrl'];
				unset($this -> query_string['ctrl']);
			}
			elseif($this->fetch('ctrl'))
			{
				/**
				 * Just in case the sysetm is using the friendly web url which may hide the params
				 * 
				 * @since Jan 24, 2015
				 */
				$this->route_url['controller'] = $this->fetch('ctrl');
			}
			
			
			if(isset($this->query_string['act']))
			{
				$this ->route_url['action'] = $this ->query_string['act'];
				unset($this -> query_string['act']);
			}
			elseif ($this->fetch('act'))
			{
				/**
				 * Just in case the sysetm is using the friendly web url which may hide the params
				 * 
				 * @since Jan 24, 2015
				 */
				$this ->route_url['action'] = $this->fetch('ctrl');
			}
			
			/**
			 * Add the language handler
			 * 
			 * @since Mar25, 2015
			 */
			if(isset($this->query_string['lg']))
			{
				$this ->route_url['lang'] = $this ->query_string['lg'];
				unset($this -> query_string['lg']);
			}
			
			if(count($this ->query_string) > 0)
			{
				//need to reset those used params otherwise will durplicate them if any
				$this ->route_url['params'] = $this ->query_string;
			}
		}
		return $this ->route_url;
	}
	
	public function return_url($controller,$action='index', $params = array(),$app = '' )
	{

		$return_url_array = array(
		//	'app' => (($app=='')?C('prog_flag.user'):$app),
			'ctrl' => (($controller=='')?'index':$controller),
			'act' => (($action=='')?'index':$action)
		);
		
		//$suffix = '-'.$return_url_array['act'];
		$suffix = '/'.$return_url_array['act'];
		if($return_url_array['act'] == 'index'){
			$suffix = '/';
		}
		
		if(defined('LANG_SEL_ENABLE'))
		{
			if(!empty($_SESSION['client_lang'])){
				$lang_dir = $_SESSION['client_lang'].DS;
				$lang_param = '&lg='.$_SESSION['client_lang'];
			}
		}
		
		
		if(C('url_friendly'))
		{
			if($app == C('prog_flag.web'))
			{
				$url = SITE_URL.$return_url_array['ctrl'].$suffix;
			}
			else
				//$url = SITE_URL.'/core/cvzapp.php?app='.$return_url_array['app'].'&ctrl='.$return_url_array['ctrl'].'&act='.$return_url_array['act'];
				$url = CFactory::getApplicationPositionURL().$return_url_array['ctrl'].$suffix;
			//$url = Web_Domain.$return_url_array['app'].'-platform?ctrl='.$return_url_array['ctrl'].'&act='.$return_url_array['act'];
		}
		else 
		{
			if($app == C('prog_flag.web'))
			{
				$url = SITE_URL.'?ctrl='.$return_url_array['ctrl'].'&act='.$return_url_array['act'].$lang_param;
			}
			else
			//$url = SITE_URL.'/core/cvzapp.php?app='.$return_url_array['app'].'&ctrl='.$return_url_array['ctrl'].'&act='.$return_url_array['act'];
				$url = CFactory::getApplicationPositionURL().'?ctrl='.$return_url_array['ctrl'].'&act='.$return_url_array['act'].$lang_param;
		}
		
		if($params)
		{
			foreach ($params as $key => $val)
			{
				$querystr .= '&'.$key.'='.$val;
			}

			$url .= (strpos($url, '?')>0)? $querystr: '?'.substr($querystr, 1,strlen($querystr));
		}
		
		return $url;
	}
	
	public function fetch($name)
	{
		return (isset($_GET[$name])?$_GET[$name]:$_POST[$name]);
	}
	
	/**
	 * @tutorial append page at the suffix
	 * 
	 * @param unknown $url
	 * @param unknown $p
	 * @return string
	 */
	public function app_page($url,$p,$exists_params = false)
	{
	    if($exists_params)
	    {
	        if($this -> query_string){
	            foreach ($this->query_string as $key => $val)
	            {
	                //to avoid duplicated P params
	                if($key == 'p') continue;
	                $querystr .= $key.'='.$val.'&';
	            }
	        }
	    }
	    
		if(strpos($url, '?') > 0){
			return $url.$querystr.'&p='.$p;
		}
		return $url.'?'.$querystr.'p='.$p;
	}
	
	/**
	 * @tutorial get ip of the visitor
	 * 
	 * @return ip string
	 */
	public function get_client_ip()
	{
		require_once LibPath.'/plugins/ipconfig/iplimit.class.php';
		
		if(!is_object(Console::getInstance('ipHttp'))){
			$ipcontroller = new iplimit();
			Console::setNewSystemLibary('ipHttp', $ipcontroller);
			
		}
		else {
			
			$ipcontroller = Console::getInstance('ipHttp');
		}
		
		$ip = $ipcontroller ->get_ip();
		
		return $ip;
	}
	
	public function post($name)
	{
		return trim($_POST[$name]);
	}
	
	public function get($name)
	{
		return trim($_GET[$name]);
	}
	
	public function permission_denied($return = false)
	{
	    if($return)
		return $this -> return_url('hey','err503');
	    loc_url($this -> return_url('hey','err503'));
	}
	
	public function page_notfound($return = false)
	{
	    if($return)
		return $this -> return_url('hey','err404');
	    loc_url($this -> return_url('hey','err404'));
	}
	
	public function program_error($return = false)
	{
	    if($return)
		return $this -> return_url('hey','err500');
	    loc_url($this -> return_url('hey','err500'));
	}
	
	public function is_mobile_device()
	{
	    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	    
	    $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
	    
	    if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap'))
	    {
	        $this -> is_mobile = true;
	    }
	    
	    return $this -> is_mobile;
	}
	
	
	public function compare_referer_url($url)
	{
	    $referUrl = $_SERVER['HTTP_REFERER'];
	    return ($url != $referUrl) ? false:true;
	}

}

?>