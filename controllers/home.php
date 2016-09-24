<?php

/** 
 * @author Cavinlz
 * 
 */
class homeController extends \Controller
{

    public function indexOp()
    {

        $from = $this -> router -> get('from');
        
        $chkfull = true;
        /**
         * 新增业务逻辑:
         * 新增传递参数from ， 已记录用户初次访问时所经过的渠道
         * i.e.
         *  adv => 微信朋友圈广告   -----   默许授权模式， 只获取openid并取消所有弹窗 (i.e.取消获取用户位置)
         *  wcp => 公众号发布的图文信息
         *  nil(无参数) => 线下广告牌
         *  
         * @date 1st Sep,2016
         */
        $session = Console::getInstance('session');
        
        if(!empty($from)){
            $suffixStr = '_'.strtoupper($from);
            $session -> data['USER_VISIT_FROM'] = $from;
        }
        else{
            $from = $session -> data['USER_VISIT_FROM'];
        }
        
        if($from == 'adv')
        {
            define('WITHOUT_FULL_AUTH',true);
            define('SKIP_GEO_LOCATION', true);   //无需获取用户地址，避免弹窗
            $chkfull = false;
        }
        
        if(!WeChat::checkWXAuth($chkfull))
        {
            
            if(!defined('WITHOUT_FULL_AUTH'))
            {
                //logg::debug('check full access');
                
                loc_url(WeChat::getFullAuthAPI('HOME'.$suffixStr));
            }
            else 
            {
                //logg::debug('check base access');
                loc_url(WeChat::getBaseAuth('HOME'.$suffixStr));
            }
            
        }
        
        $model = $this -> model('wechatcards');
        $filter['shw_flag'] =  CARD_SHOW_FLAG;
        $data['cards']  =  $model -> get_cards($page,$filter);
        
        $this -> Output('home',$data);
    }
    
}

?>