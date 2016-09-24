<?php
/**
 *
 * @author Administrator
 *        
 */
class cardController extends Controller
{

    public function viewOp()
    {
        $cardid = $this -> router -> get('id');
        
        $model = $this -> model('wechatcards');
        
        if(!$rs = $model -> check_record_exists($cardid)){
            $this -> redirect_rsp_page('Record Not Found.',GENERAL_ERROR_RETURN_CODE);
        }
       
        $wechat_card_id = $rs['wechat_card_id'];
        
        $data['card_info'] = get_card_info_via_wcp($wechat_card_id); 
            
        $cardcode_model = $this -> model('cardcodes');
        
        $data['card_codes'] = $cardcode_model -> get_certain_card_codes($cardid, $this -> router ->get('p'));
        
        /* 当不存在同名的model时， 需要手动设置页面导航设置，否则无法显示 */
        $view = $this -> view();
        $view -> set_page_vars($cardcode_model -> get_pages_vars());
        
        CLanguage::load_lang_file('cardcodes');
        CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/plugins/datatables/bootstrap-datatable.css');
        
        $this -> Output('cardview',$data);
    }
    
    
    public function editOp()
    {
        $cardid = $this -> router -> get('id');
        
        $model = $this -> model('wechatcards');
        
        if(!$rs = $model -> check_record_exists($cardid))
        {
            $this -> redirect_rsp_page('Record Not Found.',GENERAL_ERROR_RETURN_CODE);
        }
        
        
        $data['currentstep'] = 2;
        
        $wechat_card_id = $rs['wechat_card_id'];
        $data['card_key'] = $rs['id'];
        
        $data['yhg_card_info'] = $rs;
        
        /*
        if(!$rs['card'] = CMemory::mc_get('cardinfo_'.$wechat_card_id))
        {
            logg::debug('cardinfo'.$wechat_card_id.' key not exist');
            $rs = WeChat::getCardDetailsInfo($wechat_card_id);
        
            if($rs['errcode']>0) $this -> redirect_rsp_page($rs['errmsg'].'['.$rs['errcode'].']',GENERAL_ERROR_RETURN_CODE);
        
            CMemory::mc_set('cardinfo_'.$wechat_card_id, $rs['card']);
        }
        */
        $rs = WeChat::getCardDetailsInfo($wechat_card_id);
        
        if($rs['errcode']>0) $this -> redirect_rsp_page($rs['errmsg'].'['.$rs['errcode'].']',GENERAL_ERROR_RETURN_CODE);
        
        //CMemory::mc_set('cardinfo_'.$wechat_card_id, $rs['card']);
        /**
         * 'card_info' stores the data replied from WCP
         * 
         * while 'yhg_card_info' stores the data in local database
         * 
         * @var unknown
         */
        $data['card_info'] = $rs['card'];
        
        CLanguage::load_lang_file('wechatcards');
        
        $data['is_update'] = true;
        
        $this -> view('wechatcards',false);
        $this -> Output('editcard', $data);
    }
    
    
    public function updcardOp()
    {
        $cardid = $this -> router -> get('id');
        $card_category = $this -> router -> post('card_category');
        $model = $this -> model('wechatcards');
        
        if(!$rs = $model -> check_record_exists($cardid))
        {
            $this -> redirect_rsp_page('Record Not Found.',GENERAL_ERROR_RETURN_CODE);
        }
        
        $wechat_card_id = $rs['wechat_card_id'];
        
        $infoarray = $this -> build_general_coupon_card($wechat_card_id);
        
        //关键,去掉默认的Json转化中将中文转成/uxxx的默认设置
        $jsonArray = json_encode($infoarray,JSON_UNESCAPED_UNICODE);
        
        $rc = wechat::createWeChatCardUpdAPI($jsonArray);
        
        if($rc['errcode'] == 0)
        {
            /* 删除先前 Call WCP API的缓存*/
            CMemory::mc_del('cardinfo_'.$wechat_card_id);
            
            $detailsinfo = $infoarray['general_coupon']['base_info'];
        
            /**
             * 是否提交审核，false为修改后不会重新提审，true为修改字段后重新提审，该卡券的状态变为审核中。
             * 
             */
            if($rc['send_check'])
            {
                $card_status = CARD_STATUS_NOT_VERIFY;
                
                if($rs = CMemory::mc_get('card_'.$wechat_card_id)){
                    $card_status = $rs;
                }
                else
                    CMemory::mc_set('card_'.$wechat_card_id, CARD_STATUS_NOT_VERIFY);
            }
            
            /**
             * 同步本地卡数据
             * 
             * @var unknown
             */
            $card = array(
        
                    'card_cat'          =>  $card_category      ,   //卡券所属专区
                    'card_effect_term'  =>  $detailsinfo['date_info']['type']    ,
                    'begin_timestamp'   =>  $detailsinfo['date_info']['begin_timestamp']    ,
                    'end_timestamp'     =>  $detailsinfo['date_info']['end_timestamp']      ,
                    'fixed_term'        =>  $detailsinfo['date_info']['fixed_term']    ,
                    'fixed_begin_term'  =>  $detailsinfo['date_info']['fixed_begin_term']
                    
            );
        
            if($card_status){
                $card['card_status'] = $card_status;
            }
            
            
            if($model -> update_card($card, $cardid))
            {
                $carddetail['cid'] = $cardid;
                $carddetail['basic_info'] = json_encode($infoarray['card']['general_coupon']['base_info']);
        
                /*插入卡券请求详情页*/
                //$model -> new_general_card_details($carddetail);
        
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
    protected function build_general_coupon_card($wechat_card_id)
    {
    
        $resultSet = array('card_id'=>$wechat_card_id);
    
        $resultSet['general_coupon']['base_info'] = array(
    
                //'logo_url'      => $this -> router -> post('logo_url'),
                'code_type'     => $this -> router -> post('code_type'),
                'get_limit'     => (int)$this -> router -> post('get_limit'),
               // 'color'         => $this -> router -> post('color'),
                'notice'        => $this -> router -> post('notice'),
                'description'   => $this -> router -> post('description'),
               // 'can_give_friend'=>false
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
    
        //$resultSet['general_coupon']['default_detail'] = $this -> router -> post('details');
    
        return $resultSet;
    }
    
    
    
}

?>