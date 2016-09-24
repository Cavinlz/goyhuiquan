<?php

/** 
 * @author Cavinlz
 * 
 */
class wcauthController extends \Controller {
	
	/**
	 */
	public function baseOp()
	{
		
		$state = $this -> router -> fetch('state');
		$code = $this -> router -> fetch('code');
		
		if(strpos($state, '_') > 0)
		{
		    $stateArr = explode('_', $state);
		    $state = $stateArr[0];
		    $from = $stateArr[1];
		    //logg::debug('Get Returned Code:'.$from);
		}
		
		if(!empty($code))
		{
		    
			/**
			 * Successfully got the user auth , afterwards fetch the access token
			 */
			$data = WeChat::getOauthAccessToken($code);
			
			if(!$data){
			    logg::system('Failed to fire token api.');
			}
			elseif(!empty($data['errcode']))
			{
			    //return successfully
			    logg::system('[101] :'.serialize($data));
			}
			
			$model = $this -> model('members');
			
			if($rs = $model -> check_member_exits($data))
			{
				$upd_array = array(
						'lasttime'	=>	datetime()
				);
					
				$model -> cleanUp() -> update_record($rs['id'],$upd_array);
				User::register_user_session($rs);
			}
			else
			{
			    $new_array = array(
			            'user_auth'	=>	1,
			            'lasttime'	=>	datetime(),
			            'firsttime' =>  datetime(), 
			            'openid'    =>  $data['openid'],
			            'fromwhere' =>  $from
			    );
				//new member visiting
				$rs = $model -> new_member($new_array);
				$data['id'] = $rs;
				User::register_user_session($data);
			}
		}
		
		$this -> redirect_url($state);
		
	}
	
	public function fullOp()
	{
		$state = $this -> router -> fetch('state');
		$code = $this -> router -> fetch('code');
        logg::debug('Get Returned Code:'.$state);
        
		if(strpos($state, '_') > 0)
		{
		    $stateArr = explode('_', $state);
		    $state = $stateArr[0];
		    $from = $stateArr[1];
		}
		
		if(!empty($code))
		{
			/**
			 * Successfully got the user auth , afterwards fetch the access token and open id
			 */
			$data = WeChat::getOauthAccessToken($code);
			
			if(!$data){
			    logg::system('Failed to fire token api.');
			}
			elseif(!empty($data['errcode']))
			{
				//return successfully
				logg::system('[101] :'.serialize($data));	
			}
			
			if(($token = $data['access_token']) && ($openid = $data['openid']))
			{
			    //logg::debug('Got Authorized Token:'.$token);
				$userdata = WeChat::fetchUserInfo($token, $openid);
				
				if(empty($userdata['errcode']))
				{
					$userdata['user_auth'] = WEXIN_FULL_ACCESS_FLAG;
					
					//no error found
					$model = $this -> model('members');
					
					//CMemory::load_cache_file('data/province');
					//CMemory::load_cache_file('data/city');
					
					if($rs = $model -> check_member_exits($data))
					{
						
						$upd_array = array(
								'unionid'	=>	$userdata['unionid'],
								'nickname'	=>	$userdata['nickname'],
								'sex'		=>	$userdata['sex'],
								'prov_name'	=>	$userdata['province'],
								'city_name'	=>	$userdata['city'],
								'country'	=>	$userdata['country'],
								'imgurl'	=>	$userdata['headimgurl'],
								'user_auth'	=>	$userdata['user_auth'],
								'lasttime'	=>	datetime()
						);
							
						if($model -> cleanUp() -> update_record($rs['id'],$upd_array)){
							$userdata['id'] = $rs['id'];
							User::register_user_session($userdata);
						}
						
					}
					else   //新用户
					{
					    
					    $new_array = array(
					            'unionid'	=>	$userdata['unionid'],
					            'nickname'	=>	$userdata['nickname'],
					            'sex'		=>	$userdata['sex'],
					            'prov_name'	=>	$userdata['province'],
					            'city_name'	=>	$userdata['city'],
					            'country'	=>	$userdata['country'],
					            'imgurl'	=>	$userdata['headimgurl'],
					            'user_auth'	=>	$userdata['user_auth'],
					            'lasttime'	=>	datetime(),
					            'firsttime' =>  datetime(),
					            'openid'    =>  $openid,
					            'fromwhere' =>  $from
					    );
					    
						//new member visiting
						$rs = $model -> new_member($new_array);
						$new_array['id'] = $rs;
						User::register_user_session($userdata);
					}
				}
				else 
				{
					logg::system('[102] :'.serialize($userdata));
				}
				
			}
			else 
			{
			    logg::system('[103] :'.serialize($data));
			}
		}
		
		$this -> redirect_url($state);
		
	}
	
	protected function redirect_url($state)
	{
	    if(strpos($state, 'PET') !== false){
	        $id = substr($state, 3, strlen($state));
	        $url = $this -> router -> return_url('pet','index',array('k'=>$id));
	    }
	    else 
	    {
	        switch ($state)
	        {
	            case 'HOME':
	                $url = $this -> router -> return_url('home','index');
	                break;
	            case 'PRIZE':
	                $url = $this -> router -> return_url('promotion','lottery');
	                break;
	            default:
	                die('invalid state');
	                return;
	        } 
	    }
	    
	    if($url)
		  loc_url($url);
	}
	
	/**
	 * 公众平台验证接口 
	 * 
	 * 服务器配置 
	 * 
	 */
	public function authtokenOp()
	{
	   
	    $jsapiTicket = WeChat::getJsApiTicket();

	    // !! NOTES: KEY STEP, AVOID TO ZHUANYI
	    $url = urldecode($this -> router -> post('url'));
	    $timestamp = time();
	    
	    $nonceStr = createRandomStr();
	    //logg::debug($url);
	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	    
	    $signature = sha1($string);
	    
	    CMemory::load_config_file('wechat');
	    $appid = CMemory::get('wechat_config.appid');
	    $signPackage = array(
	            "appId"     => $appid,
	            "nonceStr"  => $nonceStr,
	            "timestamp" => $timestamp,
	            "url"       => $url,
	            "signature" => $signature,
	            "rawString" => $string
	    );
	    
	    //logg::debug(json_encode($signPackage));
	    
	    $code = GENERAL_SUCCESS_RETURN_CODE;
	    
	    HttpResponse::setJsonOutput(array('code'=>$code, 'body'=>$signPackage));
	}
	
	
	public function authcardtokenOp()
	{
	    $apiTicket = WeChat::getCardApiTicket();
	    $timestamp = time();
	    $nonceStr = createRandomStr();
	    //logg::debug($timestamp);
	    $cardid = $this -> router -> post('card_id');
	    //logg::debug($cardid);
	    $strArray = array($cardid,$apiTicket);
	    
	    sort($strArray);
	    //logg::debug(json_encode($strArray));
	    
	    $str = $timestamp;
	    foreach ($strArray as $val)
	    {
	        $str .= $val;
	    }
	    //logg::debug($str);
	    $signature = sha1($str);
	    //logg::debug($signature);
	    $signPackage = array(
	            
	            "api_ticket" => $apiTicket,
	            "nonceStr"  => $nonceStr,
	            "timestamp" => $timestamp,
	            "signature" => $signature,
	            
	    );
	    
	    $code = GENERAL_SUCCESS_RETURN_CODE;
	    
	    HttpResponse::setJsonOutput(array('code'=>$code, 'body'=>$signPackage));
	}
	
	
	
	public function authcardstokenOp()
	{
	    $apiTicket = WeChat::getCardApiTicket();
	    
	    $cardid = $_POST['card_id'];
	    
	    if(!is_array($cardid)){
	        HttpResponse::setJsonOutput(array('code'=>GENERAL_WARNING_RETURN_CODE, 'msg'=>'No Record Found'));
	    }
	    
	    foreach ($cardid as $val)
	    {
	        $timestamp = time();
	        $strArray = array($val,$apiTicket);
	        sort($strArray);
	        //logg::debug(json_encode($strArray));
	        $nonceStr = createRandomStr();
	        $str = $timestamp;
	        foreach ($strArray as $val)
	        {
	            $str .= $val;
	        }
	        //logg::debug($str);
	        $signature = sha1($str);
	        //logg::debug($signature);
	        
	        $signPackage[] = array(
	                 
	                "api_ticket" => $apiTicket,
	                "nonceStr"  => $nonceStr,
	                "timestamp" => $timestamp,
	                "signature" => $signature,
	                 
	        );
	        
	        $code[] = $val;
	    }
	    
	    HttpResponse::setJsonOutput(array('code'=>$cardid, 'body'=>$signPackage));
	}
	
}

?>