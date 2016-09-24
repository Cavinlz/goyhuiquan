<?php

/** 
 * @author Administrator
 * 
 */
class wechatcardsController extends \Controller
{

    /**
     * Show page of card creation form
     * 
     */
    public function createOp()
    {
        
        $getstep = $this -> router -> get('step');
        
        $data['currentstep'] = $getstep;
        
        if($getstep > 3 || empty($getstep))
        {
            //no more than 3 steps
            $data['currentstep'] = 1;
        }
        
        if($data['currentstep'] == 1) {
            $model = $this -> model('brands');
            
        }
        elseif($data['currentstep'] == 2)
        {
            $model = $this -> model('brands');
            
            
            if($brandid = base64_decode($this -> router -> get('brand')))
            {
                
                $data['brandinfo'] = $model -> get_brand_info($brandid);
                
                $logoimg = $data['brandinfo']['logo_name'];
                
                $target_folder = BasePath.DS.C('resource.brand_logo_path');
                
                $data['brandinfo']['logourl'] = $this -> router -> return_url('brands','getimg',array('s'=>$brandid,'n'=>base64_encode($logoimg)));
                
            }
            
            /*Hardcode to use custom code*/
            $data['custom_code'] = true;
        }
        
        $model = $this ->model();
        
        CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/plugins/fuelux/wizard.css');
        
        $this -> Output('createcard', $data);
    }
    
    /**
     * Upload Logo Image of weChat Card
     * 
     */
    public function uploadlogoOp()
    {
        /*品牌ID*/
        $cardbrand = $this -> router -> post('card_brand');
        $cardtype = $this -> router-> post('card_type');
        $category = $this -> router -> post('card_category');
        $cardactivity = $this -> router-> post('card_act');
        
        if($cardtype != 'GENERAL_COUPON') $this -> redirect_rsp_page(CLanguage::Text('only_support_generalcard'),GENERAL_ERROR_RETURN_CODE);
        
        $brandmodel = $this -> model('brands');
        
        $brandinfo = $brandmodel -> get_brand_info(array('id'=>$cardbrand));
            
        if(!$brandinfo) $this -> redirect_rsp_page(CLanguage::Text('brand_not_found'),GENERAL_ERROR_RETURN_CODE);
        
        $logoimg = $brandinfo['logo_name'];
        
        $target_folder = BasePath.DS.C('resource.brand_logo_path');
        
        $fname = $target_folder.DS.$cardbrand.DS.$logoimg;
            
        if(!file_exists($fname)){
             $this -> redirect_rsp_page(CLanguage::Text('brand_logo_not_found'),GENERAL_ERROR_RETURN_CODE);
        }
            	
        $model = $this->model();
        /*调用微信上传LOGO API*/
        $wechatApi = wechat::uploadCardLogoApi($fname);
            
        if(!$wechatApi) $this -> redirect_rsp_page($wechatApi['errmsg'],$wechatApi['errcode']?$wechatApi['errcode']:GENERAL_ERROR_RETURN_CODE);
       
        $auth = ($brandinfo['auth_flag'] == BRAND_AUTHORISED_FLAG)?'Y':'N';
        
        $querystr = array('step'=>2,'lg'=>base64_encode($wechatApi['url']),'brand'=>base64_encode($cardbrand),'auth'=>base64_encode($auth),'cat'=>base64_encode($category),'activity'=>base64_encode($cardactivity));
        
        /*授权商 merchant_id*/
        if($brandinfo['auth_flag'] == BRAND_AUTHORISED_FLAG) $querystr['brand_id'] = base64_encode($brandinfo['brand_id']);
        
        loc_url($this->router->return_url('wechatcards','create',$querystr));
    }
    
    /**
     * Fire the card creation API to wechat common platform
     * 
     * 
     */
    public function createcardOp()
    {
        
        $card_type = $this -> router -> post('card_type');
        $brand_id = $this -> router -> post('card_brand');
        $card_category = $this -> router -> post('category_id');
        $card_activity = $this -> router -> post('activity_id');
        switch($card_type){
            
            case 'GENERAL_COUPON':
                $infoarray['card'] = $this -> build_general_coupon_card();
                break;
            default:
                $this -> redirect_rsp_page('Invalid Request',GENERAL_ERROR_RETURN_CODE);
        }
        
        //关键,去掉默认的Json转化中将中文转成/uxxx的默认设置
        $jsonArray = json_encode($infoarray,JSON_UNESCAPED_UNICODE);
        
        $rc = wechat::createWeChatCardAPI($jsonArray);
        
        if($rc['card_id'])
        {

            $model = $this -> model();
            
            $detailsinfo = $infoarray['card']['general_coupon']['base_info'];
            
            $card_status = CARD_STATUS_NOT_VERIFY;
            
            if($rs = CMemory::mc_get('createcard_'.$rc['card_id'])){
                //若果在缓存中存在该卡的createcard数据， 说明卡券已经即时被审核通过
                $card_status = $rs;
            }
            else 
                CMemory::mc_set('createcard_'.$rc['card_id'], CARD_STATUS_NOT_VERIFY);
            
            $card = array(
                    
                    'wechat_card_id'    =>  $rc['card_id']  ,
                    'card_type'         =>  $card_type      ,
                    'card_cat'          =>  $card_category      ,   //卡券所属专区
                    'card_name'         =>  $detailsinfo['title']      ,
                    'card_status'       =>  $card_status      ,
                    'quantity'          =>  $detailsinfo['sku']['quantity']      ,
                    'brand_id'          =>  $brand_id,
                    'activity_id'       =>  $card_activity,
                    'card_effect_term'  =>  $detailsinfo['date_info']['type']    ,
                    'begin_timestamp'   =>  $detailsinfo['date_info']['begin_timestamp']    ,
                    'end_timestamp'     =>  $detailsinfo['date_info']['end_timestamp']      ,
                    'fixed_term'        =>  $detailsinfo['date_info']['fixed_term']    ,
                    'fixed_begin_term'  =>  $detailsinfo['date_info']['fixed_begin_term']
            );
            
            if($cid = $model -> new_card($card))
            {
                $carddetail['cid'] = $cid;
                $carddetail['basic_info'] = json_encode($infoarray['card']['general_coupon']['base_info']);
                
                /*插入卡券请求详情页*/
                $model -> new_general_card_details($carddetail);
                    
                loc_url($this->router->return_url('wechatcards','create',array('step'=>3,'rs'=>'success')));
                
            }
        }
        else 
        {
            $this -> redirect_rsp_page($rc['errmsg'],$rc['errcode']?$rc['errcode']:GENERAL_ERROR_RETURN_CODE);
        }
         
    }
    
    /**
     * Build up the required information for a general coupon card
     * 创建卡券接口中的 basic_info
     * 
     * 
     * @return unknown
     */
    protected function build_general_coupon_card()
    {
        
        $resultSet = array('card_type'=>'GENERAL_COUPON');
        
        $resultSet['general_coupon']['base_info'] = array(
                
                'logo_url'      => $this -> router -> post('logo_url'),
                'code_type'     => $this -> router -> post('code_type'),
                'brand_name'    => $this -> router -> post('brand_name'),
                'title'         => $this -> router -> post('title'),
                'sub_title'     => $this -> router -> post('sub_title'),
                'get_limit'     => (int)$this -> router -> post('get_limit'),
                'color'         => $this -> router -> post('color'),
                'notice'        => $this -> router -> post('notice'),
                'description'   => $this -> router -> post('description'),
                'can_give_friend'=>false
        ); 
        
        /**
         * 需自定义Code码的商家必须在创建卡券时候，设定use_custom_code为true，且在调用投放卡券接口时填入指定的Code码。
         * 
         */
        if($this -> router -> post('custom_code'))
        {
            /*将卡券设置为自定义code*/
            $resultSet['general_coupon']['base_info']['use_custom_code'] = true;
            
            /*填入该字段后，自定义code卡券方可进行导入code并投放的动作*/
            $resultSet['general_coupon']['base_info']['get_custom_code_mode'] = 'GET_CUSTOM_CODE_MODE_DEPOSIT';
            
            /*采取预存模式创建卡券, 再导入自定义卡code后再调用更新库存接口更新库存*/
            $quantity = 0;
        }
        else 
        {
            $quantity = $this -> router -> post('quantity');
        }
        
        
        if($merchant_id = $this -> router -> post('brand_id'))
        {
            /*对于代制模式， 需要传入merchant_id*/
            $resultSet['general_coupon']['base_info']['sub_merchant_info']['merchant_id'] = $merchant_id;
        }
        
        $resultSet['general_coupon']['base_info']['sku'] = array(
                
                'quantity'      => (int)$quantity,
        );
        
        $datetype = $this -> router -> post('datetype');
        
        
        if($datetype == 'DATE_TYPE_FIX_TIME_RANGE') //固定时间段
        {
            $resultSet['general_coupon']['base_info']['date_info'] = array(
            
                    'type'                  =>  $datetype,
                    'begin_timestamp'       =>  (int)(strtotime($this -> router -> post('begin_timestamp'))),
                    'end_timestamp'         =>  (int)(strtotime($this -> router -> post('end_timestamp'))),
            );
        }
        elseif($datetype == 'DATE_TYPE_FIX_TERM') //固定时长
        {
            $resultSet['general_coupon']['base_info']['date_info'] = array(
            
                    'type'                  =>  $datetype,
                    'fixed_term'            =>  $this -> router -> post('fixed_term'),
                    'fixed_begin_term'      =>  $this -> router -> post('fixed_begin_term'),
            );
        }
        
        $resultSet['general_coupon']['default_detail'] = $this -> router -> post('details');
        
        return $resultSet;
    }
    
    
    /**
     * 删除卡券
     * 
     */
    public function delcardOp()
    {
        if(false === $this -> router -> compare_referer_url($this -> router -> return_url('listview','cards'))) {
            HttpResponse::permissionDenied();
        }
        
        $card_id = $this -> router -> post('card');
        
        if(empty($card_id)){
            HttpResponse::invalidRequest(1001);
        }
        
        $model = $this -> model();
        
        if(!$cardinfo = $model -> get_card($card_id)){
             HttpResponse::invalidRequest(1002);
        }
        
        $wechat_card_id = $cardinfo['wechat_card_id'];

        $rc = wechat::delWeChatCardAPI($wechat_card_id);

        if($rc['errcode'] > 0){
            HttpResponse::outputJsonReturn($rc['errmsg'],$rc['errcode']);
        }
        
        $model = $model -> update_card(array('shw_flag'=>CARD_DELETE_FLAG),$card_id);
        
        HttpResponse::outputJsonReturn('OK');
    }
    
    public function configtesterOp()
    {
        $nameArr = array('cavin_zhang');
        
        $rs = wechat::testerWhitelist($nameArr);
        
        print_r($rs);
    }
    
    /**
     * 更新卡的投放状态
     * 
     */
    public function updstatusOp()
    {
            $key = $this -> router -> post('k');
            $status = $this -> router -> post('status');
            
            if($status == 'false'){
                $statusArr = array('shw_flag'=>CARD_NOSHOW_FLAG);
            }
            elseif($status == 'true'){
                $statusArr = array('shw_flag'=>CARD_SHOW_FLAG);
            }
            else return false;
            
            $model = $this -> model();
            
            $model -> update_card($statusArr, $key);
    }
}

?>