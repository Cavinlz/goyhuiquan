<?php

/** 
 * @author Cavinlz
 * 
 */
class eventsController extends \Controller
{

    protected $eventhandled = 2;
    
    public function wcnotifyOp()
    {
 
        $postData = $HTTP_RAW_POST_DATA ? $HTTP_RAW_POST_DATA : file_get_contents('php://input');

        logg::debug('收到微信平台推送的消息：' . $postData);
        
        if(empty($postData)){
            $this -> authtoken();
        }
        else{
            
            $xmlObj = simplexml_load_string($postData, 'SimpleXMLElement', LIBXML_NOCDATA);
            //$xmlObj = simplexml_load_string($postData);
            
            logg::debug('请求事件：'.($xmlObj -> Event));
            
            if(false === $xmlObj){
            
                echo 'Parse Xml File Error Occurred.';
                logg::debug('Parse Xml File Error Occurred.');
                return;
            
            }
            
            switch($xmlObj -> Event)
            {
                case 'card_pass_check':  //卡券审核结果
                    $this -> cardpasscheck($xmlObj);
                    break;
                case 'card_not_pass_check': 
                    $this -> cardpasscheck($xmlObj,false);
                    break;
                case 'user_del_card': //用户在卡包里删除卡券
                    $this -> userdelcard($xmlObj);
                    break;
                case 'user_get_card':
                    $this -> usergetcard($xmlObj);
                    break;
                case 'VIEW':
                    die(" ");
                    break;
                default:
                    //return 'success';
                    logg::debug('invalid event');
                    die(" ");
            }
            echo ' ';
            
            if(is_object($xmlObj)){
                $strArr = get_object_vars($xmlObj);
            }
            
            if(is_array($strArr)){
                
                $event_details = json_encode($strArr);
                
                $array = array(
                        'event_key' =>  $xmlObj -> Event,
                        'event_val' =>  $event_details,
                        'datetime'  => datetime(),
                        'handled'   =>  $this -> eventhandled,
                        'sessionid' => session_id()
                );
                
                $this -> newevent($array);
                
            }
        }
        
        
    }
    
    /**
     * 审核卡券事件
     *
     */
    protected function cardpasscheck($xmlObj, $pass = true)
    {
        
        $card_id = (String)$xmlObj -> CardId;
        
        $vals = array(
               'ToUserName'     => (String)$xmlObj -> ToUserName,
               'FromUserName'   => (String)$xmlObj -> FromUserName,
                'CreateTime'    => (String)$xmlObj -> CreateTime,
                'CardId'        => $card_id
        );
        
        $event_details = serialize($vals);
        
        $array = array(
                'event_key' =>  $xmlObj -> Event,
                'event_val' =>  $event_details,
                'datetime'  => datetime()
        );
        
        //$this -> newevent($array);
         
        $mckey = 'createcard__'.$card_id;
        
        $status = ($pass)?CARD_STATUS_VERIFY_OK:CARD_STATUS_VERIFY_FAIL;
        
        $model = $this -> model('wechatcards');
        
            /*
             * 1) 若果在缓存中没有存在该卡的createcard数据，有两种可能， 一种是刚刚创建卡，审核结果比插入本地数据速度更快
             * 2) 另一种可能是缓存过期了， 意味着卡券创建后一段时间才接受到审核结果
            */
            
       if($model -> check_record_exists('',array('wechat_card_id'=>$card_id))){
                
           if(!$model -> update_card(array('card_status'=>$status), array('wechat_card_id'=>$card_id))){
                    logg::write('Failed to update card status','Event');
           }
                
       }
       else
            CMemory::mc_set($mckey, $status);
            
        $this->eventhandled = 1;
    }
    
    /**
     * 用户端在卡包删除卡券事件
     * 
     * @param unknown $xmlObj
     * 
     * <xml> 
     * <ToUserName><![CDATA[toUser]]></ToUserName> 
     * <FromUserName><![CDATA[FromUser]]></FromUserName> 
     * <CreateTime>123456789</CreateTime> 
     * <MsgType><![CDATA[event]]></MsgType> 
     * <Event><![CDATA[user_del_card]]></Event> 
     * <CardId><![CDATA[cardid]]></CardId> 
     * <UserCardCode><![CDATA[12312312]]></UserCardCode>
     * </xml>
     * 
     */
    protected function userdelcard($xmlObj)
    {
        $cardcode = (String)$xmlObj -> UserCardCode;
        $card_id = (String)$xmlObj -> CardId;
        
        if(empty($cardcode) || empty($card_id)) return;
            
        $model = $this -> model('cardcodes');
        
        if($code = $model -> get_certain_code(array('card_id'=>$card_id,'code_no'=>$cardcode)))
        {
            
            $code_id = $code['id'];
            
            if(!$model -> update_code(array('code_status'=>CARD_CODE_STATUS_DELETE,'del_time'=>datetime()), $code_id)){
                logg::system('Del Event: Failed to update card code '.$cardcode.'['.$card_id.'] status');
                return;
            }
            $this->eventhandled = 1;
        }

    }
    
    /**
     * 用户领取卡券事件
     * 
     * <xml> 
     *  <ToUserName><![CDATA[toUser]]></ToUserName> 
     *  <FromUserName><![CDATA[FromUser]]></FromUserName> 
     *  <FriendUserName><![CDATA[FriendUser]]></FriendUserName> 
     *  <CreateTime>123456789</CreateTime> 
     *  <MsgType><![CDATA[event]]></MsgType> 
     *  <Event><![CDATA[user_get_card]]></Event> 
     *  <CardId><![CDATA[cardid]]></CardId> 
     *  <IsGiveByFriend>1</IsGiveByFriend>
     *  <UserCardCode><![CDATA[12312312]]></UserCardCode>
     *  <OuterId>0</OuterId>
     * </xml>
     * 
     * @param unknown $xmlObj
     */
    protected function usergetcard($xmlObj)
    {
        $cardcode = (String)$xmlObj -> UserCardCode;
        $card_id = (String)$xmlObj -> CardId;
        
        $openid = (String)$xmlObj -> FromUserName;
        
        if(empty($cardcode) || empty($card_id) || empty($openid)) return;
        
        $model = $this -> model('cardcodes');
        
        $condition = array('code_no'=>$cardcode,'card_id'=>$card_id);
        
        if($code = $model -> get_certain_code($condition))
        {
        
            $code_id = $code['id'];
            $card_cid = $code['cid'];
            $wcmodel = $this -> model('wechatcards');
            
            if(!$wcmodel -> update_card(array('quantity'=>'`quantity`- 1'),$card_cid)){
                logg::system('Failed to update card quantity');
            }
            
            $code_id = $code['id'];
        
            $session = $this -> load('session');
            
            if(!$model -> cleanUp() -> update_code(array('code_status'=>CARD_CODE_STATUS_PICKED_UP,'get_time'=>datetime(),'openid'=>$openid,'get_place'=>$session -> data['YHG_MY_CURR_GEO']), $code_id)){
                logg::system('Get Event: Failed to update card code '.$cardcode.'['.$card_id.'] status');
                return;
            }
            $this->eventhandled = 1;
        }
        
    }
    
    
    /**
     * 微信服务器Token验证，WCP基础配置项
     * 
     */
    protected function authtoken()
    {
    
        $signature = $_GET["signature"];
        
        $timestamp = $_GET["timestamp"];
        
        $nonce = $_GET["nonce"];
        
        $echostr = $_GET["echostr"];
        
        if(empty($signature))  echo 'Invalid Request.';
        
        $token = 'YHUIGO';
        
        $tmpArr = array($token, $timestamp, $nonce);
        
        sort($tmpArr, SORT_STRING);
        
        $tmpStr = implode( $tmpArr );
        
        $tmpStr = sha1( $tmpStr );
        
        
        if( $tmpStr == $signature ){
        
            echo $echostr;
        
        }else{
        
            echo 'Invalid Request.';
        }
        
    }
    
    
    protected function newevent($array)
    {
        $model = $this -> model();
        
        if($model->insert($array,'events')){
            echo 'success';
        }
        else{
            echo 'Failed';
        }
    }
    
    /********************************************************8
     * 
     * 用于接收天虹卡券核销通知的notify_url，
     * 当用户在优惠券核销券码，天虹主动向该地址发送核销成功信息
     * 
     * HTTP GET application/x-www-form-urlencoded;charset=UTF-8
     * 
     * 发送参数
     *
     * @cp_code	    string	是	-	券编码
     * @timestamp	string	是	-	核销时间
     * @token	    string	是	-	验证规则，待定    
     * 
     */
    public function thnotifyOp()
    {
        $cardcode = $this -> router -> get('cp_code');
        $timestamp = $this -> router -> get('timestamp');
        
        logg::debug('收到天虹平台推送的消息 ===== >' . $cardcode);
        
        if(empty($cardcode) || empty($timestamp)) die('Invalid Request.[err:101]');
        
        $date = date('Y-m-d H:i:s', $timestamp);
        
        $model = $this -> model('cardcodes');
        
        $condition = array('code_no'=>$cardcode,'indicator'=>'tianhong');
        
        if($code = $model -> get_certain_code($condition))
        {
            $code_id = $code['id'];
            $card_id = $code['card_id'];
            
            /** Verify the code's status before we do any actions */
            $checkcodeconsumed = WeChat::checkCodeConsumedStatus($cardcode, $card_id);
            
            if($checkcodeconsumed['errcode'] > 0 ){
                die($checkcodeconsumed['errmsg']);
            }
            
            
            $cardconsumed = WeChat::consumeCodeAPI($cardcode, $card_id);
            
            if($cardconsumed['errcode'] > 0){
                die($cardconsumed['errmsg']);
            }
            
            if(!$model -> cleanUp() -> update_code(array('code_status'=>CARD_CODE_STATUS_CONSUMED,'used_time'=>$date), $code_id)){
                logg::system('Get Tianhong Event: Failed to update card code '.$cardcode.' status');
                return;
            }
            
            $this->eventhandled = 1;
            
            $strArr = array('cp_code'=>$cardcode,'timestamp'=>$timestamp);
            
            $event_details = json_encode($strArr);
            
            $array = array(
                    'event_key' =>  'tianhong_code_consumed',
                    'event_val' =>  $event_details,
                    'datetime'  =>  datetime(),
                    'handled'   =>  $this -> eventhandled,
                    'sessionid' => session_id()
            );
            
            $this -> newevent($array);
            
        }
        else 
        {
            echo 'Invalid Request. [err:102]';
        }
    }
}

?>