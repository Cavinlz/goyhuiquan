<?php
addFunc('CurlRequestHandler');
/** 
 * @author Cavinlz
 * 
 */
class WeChat {
	
    protected static $debug_mode = true;
    protected static $uphost = null;
    protected static $downhost = null;
    protected static $apiurl = null;
    
	/**
	 */
	public static function checkWXAuth($chkfull = false)
	{
		if(!defined('IS_WECHAT_BROWSER')) return true;
		
		$session = Console::getInstance('session');
		
		if($session -> data ['WC_USER_OPENID'])
		{
			if($chkfull)
			{
				return self::checkWXFullAuth();
			}
			
			return true;
		}
		
		return false;
	}
	
	public static function checkWXFullAuth()
	{
	    $session = Console::getInstance('session');
	    
	    if($session -> data['WC_USER_AUTH'] == WEXIN_FULL_ACCESS_FLAG){
	        return true;
	    }
	    else{
	        return false;
	    }
	}
	
	public static function getBaseAuth($state)
	{
		CMemory::load_config_file('wechat');
		
		$appid = CMemory::get('wechat_config.appid');
		
		$router = Console::getInstance('router');
		
		$return_url = $router -> return_url('wcauth','base');
		
		$baselink = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=".urlencode($return_url)."&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
		
		loc_url($baselink);
	}
	
	public static function getFullAuthAPI($state)
	{
		CMemory::load_config_file('wechat');
	
		$appid = CMemory::get('wechat_config.appid');
	
		$router = Console::getInstance('router');
	
		$return_url = $router -> return_url('wcauth','full');
	
		$baselink = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=".urlencode($return_url)."&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
		
		//loc_url($baselink);
		return $baselink;
	}
	
	
	public static function getOauthAccessToken($code)
	{
		
		CMemory::load_config_file('wechat');
		
		$appid = CMemory::get('wechat_config.appid');
		$appsec = CMemory::get('wechat_config.appsec');
		
		if(empty($appid) || empty($appsec))
		{
			Logg::system('Unable to get the weixin api info.');
			return;
		}
		
		$api = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsec&code=$code&grant_type=authorization_code";
		
		$curl = new CurlRequestHandler($api);
		
		$data = $curl -> do_curl_get_request();
		
		if($data === FALSE)
		{
			//in case failed in the Curl Request
			$data = file_get_contents($api);
		}
		
		$token_array = json_decode($data,true);
		
		return $token_array;
	}
	
	
	/**
	 * 获取 Ticket (access_token) API
	 *
	 * Desp: access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token
	 *       access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效
	 *
	 * URL: https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
	 * 
	 */
	public static function getClientCredentialToken()
	{
	    $tokenPath = BasePath.DS.C('resource.cache_data').DS."access_token.json";
	    
	    if(file_exists($tokenPath))
	        $data = json_decode(file_get_contents($tokenPath));
	    
	    $regenerate = true;
	    
	    if($data)
	    {
	        //Logg::debug($data -> expire_time);
	        if ($data -> expire_time < time()) {
	             
	        } else {
	            $access_token = $data->access_token;
	            $regenerate = false;
	        }
	    }
	    
	    if($regenerate)
	    {
	        //logg::debug('Regenerate Access Token');
    	    CMemory::load_config_file('wechat');
    	    $appid = CMemory::get('wechat_config.appid');
    	    $appsec = CMemory::get('wechat_config.appsec');
    	    
    	    if(empty($appid) || empty($appsec))
    	    {
    	        Logg::system('Unable to get the weixin api info.');
    	        return;
    	    }
    	    
    	    $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsec";
    	    
    	    $curl = new CurlRequestHandler($api);
    	    
    	    $data = $curl -> do_curl_get_request();
    	    
    	    if($data === FALSE)
    	    {
    	        //in case failed in the Curl Request
    	        $data = file_get_contents($api);
    	    }
    	    $res = json_decode($data);
    	    
    	    $access_token = $res->access_token;
    	    
    	    if ($access_token) {
    	        
    	        $newdata = array();
    	        $newdata['expire_time'] = time() + 7000;
    	        $newdata['access_token'] = $access_token;
    	        $fp = fopen($tokenPath, "w");
    	        
    	        fwrite($fp, json_encode($newdata));
    	        fclose($fp);
    	    }
	        
	    }
	    //logg::debug('Access Token:'.$access_token);
	    return $access_token;
	}
	
	
	/**
	 * Get previous Ticket (access_token)
	 *
	 * In case expired,  fire another API::getClientCredentialToken to get a new one
	 * 
	 */
	public static function getJsApiTicket()
	{
	    $tokenPath = BasePath.DS.C('resource.cache_data').DS."jsapi_ticket.json";
	    
	    if(file_exists($tokenPath))
	       $data = json_decode(file_get_contents($tokenPath));
	    
	    $regenerate = true;
	    
	    if($data)
	    {
	        
	        //logg::debug($data -> expire_time);
	        
	        if ($data -> expire_time < time()) {
	            
	        } else {
	            $ticket = $data->jsapi_ticket;
	            $regenerate = false;
	        }
	    }
	    
	    if($regenerate)
	    {
	        $accessToken =self::getClientCredentialToken();
	         
	        //$accessToken = $accessTokenArray['access_token'];
	         
	        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
	         
	        $curl = new CurlRequestHandler($url);
	        
	        $data = $curl -> do_curl_get_request();
	         
	        if($data === FALSE)
	        {
	            //in case failed in the Curl Request
	            $data = file_get_contents($url);
	        }
	        //logg::debug('APITicket:'.$data);
	        $res = json_decode($data);
	         
	        $ticket = $res->ticket;
	         
	        if ($ticket) {
	            logg::debug('regenerate the json file');
	            $newdata = array();
	            $newdata['expire_time'] = time() + 7000;
	            $newdata['jsapi_ticket'] = $ticket;
	            $fp = fopen($tokenPath, "w");
	            fwrite($fp, json_encode($newdata));
	            fclose($fp);
	        }
	    }
	    //logg::debug('Ticket:'.$ticket);
	    return $ticket;
	}
	
	public static function fetchUserInfo($token, $openid)
	{
		CMemory::load_config_file('wechat');
		
		$appid = CMemory::get('wechat_config.appid');
		$appsec = CMemory::get('wechat_config.appsec');
		
		if(empty($appid) || empty($appsec))
		{
			Logg::system('Unable to get the weixin api info.');
			return;
		}
		
		$api = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		
		$curl = new CurlRequestHandler($api);
		
		$data = $curl -> do_curl_get_request();
		
		if($data === FALSE)
		{
			//in case failed in the Curl Request
			$data = file_get_contents($api);
		}
		//self::debug('Fetched Full User Info API Response: ====>'. ($data));
		$userinfo_array = json_decode($data,true);
		//logg::write($data);
		return $userinfo_array;
	}
	
	
	/**
	 * Upload Card Logo Image API
	 * 
	 * Param: Local path of the image
	 * 
	 */
	public static function uploadCardLogoApi($logopath)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    $fileds = array('buffer'=>'@'.$logopath,'access_token'=>$accessToken);
	    $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=$accessToken";
	    
	    $curl = new CurlRequestHandler($url);
	     
	    $data = $curl -> do_curl_post_request($fileds);
	    

	    if($data === FALSE)
	    {
	        //in case failed in the Curl Request
	        //$data = file_get_contents($api);
	        echo 'false';
	    }
	    
	    $info_array = json_decode($data,true);
	    
	    return $info_array;
	    
	}
	
	public static function createWeChatCardAPI($postJsonString)
	{

	    $accessToken = wechat::getClientCredentialToken();
	    $fileds = array('access_token'=>$accessToken);
	    self::$apiurl = "https://api.weixin.qq.com/card/create?access_token=$accessToken";
	     
	    $curl = new CurlRequestHandler(self::$apiurl);
	    
	    self::debug('Fired Card Creation API Request: ====>'. $postJsonString);
	    
	    self::$uphost = $postJsonString;
	    
	    self::$downhost = $curl -> do_curl_post_request($postJsonString);
	     
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    self::debug('Got Card Creation API Response: ====>'. (self::$downhost));
	    
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	    
	    return $info_array;
	}
	
	public static function createWeChatCardUpdAPI($postJsonString)
	{
	
	    $accessToken = wechat::getClientCredentialToken();
	    $fileds = array('access_token'=>$accessToken);
	    self::$apiurl = "https://api.weixin.qq.com/card/update?access_token=$accessToken";
	
	    $curl = new CurlRequestHandler(self::$apiurl);
	     
	    self::debug('Fired Card Creation API Request: ====>'. $postJsonString);
	     
	    self::$uphost = $postJsonString;
	     
	    self::$downhost = $curl -> do_curl_post_request($postJsonString);
	
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    self::debug('Got Card Creation API Response: ====>'. (self::$downhost));
	     
	    $info_array = json_decode(self::$downhost,true);
	     
	    self::transactionLogging();
	     
	    return $info_array;
	}
	
	/**
	 * 删除卡券 API
	 * 
	 * 
	 */
	public static function delWeChatCardAPI($cardid)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    
	    self::$apiurl = "https://api.weixin.qq.com/card/delete?access_token=$accessToken";
	    
	    $params = array('card_id'=>$cardid);
	    
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	    
	    $curl = new CurlRequestHandler(self::$apiurl);
	    
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	    
	    
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	    
	    return $info_array;
	}
	
	/**
	 * 导入卡券Code API
	 *
	 *
	 */
	public static function importCardCode($postString)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    self::$apiurl = "http://api.weixin.qq.com/card/code/deposit?access_token=$accessToken";
	    
	    //require json format to post the data
	    self::$uphost = json_encode($postString);
	    
	    $curl = new CurlRequestHandler(self::$apiurl);
	     
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	    
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	     
	    return $info_array;
	}
	
	/**
	 * 检查导入卡券Code成功的数目 API
	 *
	 *
	 */
	public static function checkImportedCodesCount($cardid)
	{
	    $accessToken = wechat::getClientCredentialToken();
	     
	    self::$apiurl = "http://api.weixin.qq.com/card/code/getdepositcount?access_token=$accessToken";
	     
	    $params = array('card_id'=>$cardid);
	     
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	     
	    $curl = new CurlRequestHandler(self::$apiurl);
	     
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	     
	     
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	     
	    $info_array = json_decode(self::$downhost,true);
	     
	    self::transactionLogging();
	     
	    return $info_array;
	}
	/**
	 * 获取导入卡券Code的列表API
	 *
	 *
	 */
	public static function getImportedCodesList($postString)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    
	    self::$apiurl = "http://api.weixin.qq.com/card/code/checkcode?access_token=$accessToken";
	    
	    //require json format to post the data
	    self::$uphost = json_encode($postString);
	    
	    $curl = new CurlRequestHandler(self::$apiurl);
	    
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	    
	    
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	    
	    return $info_array;
	}
	/**
	 * 更新卡券Code的库存 Quantity API
	 *
	 *
	 */
	public static function updateCardQuantity($cardid, $count, $byincr = true)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    
	    self::$apiurl = "https://api.weixin.qq.com/card/modifystock?access_token=$accessToken";
	    
	    
	    $params = array('card_id'=>$cardid);
	    
	    if($byincr){
	        $params['increase_stock_value'] = $count;
	    }
	    else {
	        $params['reduce_stock_value'] = $count;
	    }
	    
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	    
	    $curl = new CurlRequestHandler(self::$apiurl);
	    
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	    
	    
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	    
	    return $info_array;
	}
	/**
	 * 设置测试白名单
	 *
	 *
	 */
	public static function testerWhitelist($nameArray)
	{
	    $accessToken = wechat::getClientCredentialToken();
	     
	    self::$apiurl = "https://api.weixin.qq.com/card/testwhitelist/set?access_token=$accessToken";
	    
	    $params = array('username'=>$nameArray);
	    
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	     
	    $curl = new CurlRequestHandler(self::$apiurl);
	     
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	     
	     
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	     
	    $info_array = json_decode(self::$downhost,true);
	     
	    self::transactionLogging();
	     
	    return $info_array;
	}
	
	public static function debug($msg)
	{
	    if(self::$debug_mode) logg::write($msg);
	}
	
	public static function transactionLogging()
	{
	    $ctrl = Console::getInstance('controller');
	    $model = $ctrl -> model();
	    $model -> insert(array(
	            
	            "uphost"   => self::$uphost    ,
	            "downhost" => self::$downhost  ,
	            "request_url"  => self::$apiurl,
	            "datetime" => datetime()
	            
	    ),'wc_transactions');
	}
	
	
	public static function getCardApiTicket()
	{
	    $tokenPath = BasePath.DS.C('resource.cache_data').DS."api_ticket.json";
	     
	    if(file_exists($tokenPath))
	        $data = json_decode(file_get_contents($tokenPath));
	     
	    $regenerate = true;
	     
	    if($data)
	    {
	         
	        //logg::debug($data -> expire_time);
	         
	        if ($data -> expire_time < time()) {
	             
	        } else {
	            $ticket = $data->api_ticket;
	            $regenerate = false;
	        }
	    }
	     
	    if($regenerate)
	    {
	        $accessToken =self::getClientCredentialToken();
	
	        //$accessToken = $accessTokenArray['access_token'];
	
	        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token=$accessToken";
	
	        $curl = new CurlRequestHandler($url);
	         
	        $data = $curl -> do_curl_get_request();
	
	        if($data === FALSE)
	        {
	            //in case failed in the Curl Request
	            $data = file_get_contents($url);
	        }
	        logg::debug('card_api_ticket:'.$data);
	        $res = json_decode($data);
	
	        $ticket = $res->ticket;
	
	        if ($ticket) {
	            logg::debug('regenerate the json file');
	            $newdata = array();
	            $newdata['expire_time'] = time() + 7000;
	            $newdata['api_ticket'] = $ticket;
	            $fp = fopen($tokenPath, "w");
	            fwrite($fp, json_encode($newdata));
	            fclose($fp);
	        }
	    }
	    logg::debug('Ticket:'.$ticket);
	    return $ticket;
	}
	
	/**
	 * 查询卡券码的状态
	 * 
	 * 接口可以查询当前code是否可以被核销并检查code状态。
	 * @param unknown $code
	 * @param unknown $cardid
	 */
	public static function checkCodeConsumedStatus($code, $cardid)
	{
	    $accessToken = wechat::getClientCredentialToken();
	    
	    self::$apiurl = "https://api.weixin.qq.com/card/code/get?access_token=$accessToken";
	    
	    $params = array('code'=>$code, 'card_id'=>$cardid,'check_consume'=>true);
	    
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	    
	    $curl = new CurlRequestHandler(self::$apiurl);
	    
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	    
	    
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	    
	    $info_array = json_decode(self::$downhost,true);
	    
	    self::transactionLogging();
	    
	    return $info_array;
	}
	
	/**
	 * 核销卡券接口
	 * 
	 * @param unknown $code
	 * @param unknown $cardid
	 */
	public static function consumeCodeAPI($code, $cardid)
	{
	    $accessToken = wechat::getClientCredentialToken();
	
	    self::$apiurl = "https://api.weixin.qq.com/card/code/consume?access_token=$accessToken";
	     
	    $params = array('code'=>$code, 'card_id'=>$cardid);
	     
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	
	    $curl = new CurlRequestHandler(self::$apiurl);
	
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	
	
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	
	    $info_array = json_decode(self::$downhost,true);
	
	    self::transactionLogging();
	
	    return $info_array;
	}
	
	
	/**
	 * 查看卡券详情
	 *
	 * @param unknown $cardid
	 */
	public static function getCardDetailsInfo($cardid)
	{
	    $accessToken = wechat::getClientCredentialToken();
	
	    self::$apiurl = "https://api.weixin.qq.com/card/get?access_token=$accessToken";
	
	    $params = array('card_id'=>$cardid);
	
	    //require json format to post the data
	    self::$uphost = json_encode($params);
	
	    $curl = new CurlRequestHandler(self::$apiurl);
	
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	
	
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	
	    $info_array = json_decode(self::$downhost,true);
	
	    self::transactionLogging();
	
	    return $info_array;
	}
	
	
	/**
	 * 创建自定义菜单
	 * 
	 */
	public static function createCustomMenuAPI($postString)
	{
	    $accessToken = wechat::getClientCredentialToken();
	     
	    self::$apiurl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
	     
	    self::$uphost = $postString;
	     
	    $curl = new CurlRequestHandler(self::$apiurl);
	     
	    self::$downhost = $curl -> do_curl_post_request(self::$uphost);
	     
	     
	    if(self::$downhost === FALSE)
	    {
	        return false;
	    }
	     
	    $info_array = json_decode(self::$downhost,true);
	     
	    self::transactionLogging();
	     
	    return $info_array;
	}
}

?>