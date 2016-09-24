<?php

/** 
 * @author Administrator
 * 
 */
class wechatcardsView extends \FormView
{

    /**
     */
    public function config_form_fieldsets()
	{
	    
	    switch($this -> data['currentstep'])
	    {
	        case '2':
	            $this -> data['step1'] = 'complete';
	            $this -> data['step2'] = 'active';
	            $this -> config_second_step_fieldset();
	            break;
	        case '3':
	            $this -> data['step1'] =  $this -> data['step2'] = 'complete';
	            $this -> data['step3'] = 'active';
	            if($this -> router -> get('rs') == 'success')
	               $this -> success_committed_step();
	            break;
	        default:
	            $this -> data['step1'] = 'active';
	            $this -> config_first_step_fieldset();
	    }
	    
	    $this -> data['submit_button_val'] = CLanguage::Text('BUTTON.NEXT');
	    $this -> data['submit_button_icon'] = 'angle-right';
	    
	    parent::config_form_fieldsets();
	}
	
	/**
     * Uploading Logo Image Step
     *
	 */
	protected function config_first_step_fieldset()
	{
	    $logo_info = array(
	             
	            'title'		      =>	CLanguage::Text('card_logo_upload')				,
	            'subtitle'		  =>	CLanguage::Text('card_logo_upload_tips')				,
	            'icon'            =>    'upload-alt'
	  
	    );
	    
	    $form = array(
	    
	            'target' => "frobj",
	            'action' => $this -> router -> return_url('wechatcards','uploadlogo'),
	            'enctype'=> "multipart/form-data"
	    );
	    
	    $this -> data['formaction'] = $this -> router -> return_url('wechatcards','uploadlogo');
	    
	    $form = $this ->create_nonform_obj();
	    	
	    $form  //-> addElement(new CSelect(CLanguage::Text('card_category'),'card_category',get_card_categories(), array('required'=>true,'value'=>'','class'=>'span12')))
	           -> addElement(new CSelect(CLanguage::Text('card_act'),'card_act',get_activity_list($this->load('controller')), array('required'=>true,'class'=>'span12')))
	           -> addElement(new CSelect(CLanguage::Text('card_type'),'card_type',card_types(), array('required'=>true,'value'=>'GENERAL_COUPON','class'=>'span12')))
	           -> addElement(new CSelect(CLanguage::Text('card_brand'),'card_brand',get_brand_list($this->load('controller')), array('required'=>true,'class'=>'span12')))
	           //-> addElement(new CFile(CLanguage::Text('card_logo'),'prodimg', array('requried'=>true))) 
	           //-> addElement(new CHidden('','init_img',array('value'=>$this->data['mac_pic'])))
	           -> addElement(new CHidden('','k',array('value'=>$this->data['id'])));
	    
	    $logo_info['fieldset']	= $form;
	    
	    
	    
	    array_push($this -> form_fieldsets, $logo_info);
	}
	
	/**
	 * Step 2: Fill in the information of Card API
	 * 
	 */
	protected function config_second_step_fieldset()
	{
	    $card_info = array(
	    
	            'title'		      =>	CLanguage::Text('card_info')				,
	            'subtitle'		  =>	CLanguage::Text('card_tips')				,
	            'icon'            =>    'credit-card'
	    
	    );
	    
	    $form = $this -> create_nonform_obj();
	    
	    if(!$this -> data['is_update'])
	    {
	        $this -> data['formaction'] = $this -> router -> return_url('wechatcards','createcard');
	        $brandname = $this->data['brandinfo']['brand_name'];
	        
	        $disabled = '';
	    }
	    else 
	    {
	        /**
	         * 更新卡券的操作
	         * @var unknown
	         */
	        $isupdate = true;
	        $this -> data['formaction'] = $this -> router -> return_url('card','updcard').'/'.$this -> data['card_key'].'/';
            $card_details = $this->data['card_info'];
	        $card_basic_info = $card_details[strtolower($card_details['card_type'])]['base_info'];
	        
	        
	        
	        $brandname = $card_basic_info['brand_name'];
	        
	        $begin_timestamp = date('Y-m-d H:i:s',$card_basic_info['date_info']['begin_timestamp']);
	        $end_timestamp = date('Y-m-d H:i:s',$card_basic_info['date_info']['end_timestamp']);
	        
	        $card_basic_info['date_info']['begin_timestamp'] = $begin_timestamp;
	        $card_basic_info['date_info']['end_timestamp'] = $end_timestamp;
	        
	        /*Disabled some form input which is not allowed to edit accroding to WCP*/
	        $disabled = 'd';
	        
	        $this -> data['card_basic_info'] = $card_basic_info;
	        
	        $form  -> addElement(new CSelect(CLanguage::Text('card_category'),'card_category',get_card_categories(), array('required'=>true,'value'=>$this -> data['yhg_card_info']['card_cat'],'class'=>'span12')));
	    }
	    
	    
	    $logo = base64_decode($this->router->get('lg'));
	    
	    
	    
	    $form  
	    -> addElement(new CSelect(CLanguage::Text('BASICINFO.CODE_TYPE'),'code_type',card_code_types(), array('required'=>true,'value'=>$card_basic_info['code_type'],'class'=>'span12')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.brand_name'),'brand_name',array('placeholder'=>' ','required'=>true,'value'=>$brandname,'class'=>'span12','disable'.$disabled=>'')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.title'),'title',array('placeholder'=>' ','required'=>true,'value'=>$card_basic_info['title'],'class'=>'span12','disable'.$disabled=>'')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.subtitle'),'sub_title',array('placeholder'=>' ','value'=>$card_basic_info['sub_title'],'class'=>'span12','disable'.$disabled=>'')))
	    -> addElement(new CSelect(CLanguage::Text('BASICINFO.color'),'color',card_bg_colors(), array('required'=>true,'value'=>$card_basic_info['color'],'class'=>'span12')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.notice'),'notice',array('placeholder'=>' ','required'=>true,'value'=>$card_basic_info['notice'],'class'=>'span12')))
	    -> addElement(new CTextarea(CLanguage::Text('BASICINFO.description'),'description',array('placeholder'=>'请填写使用本优惠券的注意事项','value'=>$card_basic_info['description'],'class'=>'span12')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.setlimit'),'get_limit',array('placeholder'=>' ','required'=>true,'value'=>(empty($card_basic_info['get_limit']))?$card_basic_info['get_limit']:1,'class'=>'span12')))
	    ;
	    
	    if(!$this -> data['custom_code'] && !$isupdate)
	    {
	       $form -> addElement(new CTextbox(CLanguage::Text('BASICINFO.quantity'),'quantity',array('placeholder'=>' ','required'=>true,'value'=>$card_basic_info['quantity'],'class'=>'span12')));    
	    }
	    $form
	    -> addElement(new CSelect(CLanguage::Text('BASICINFO.datetype'),'datetype',card_efficient_type(), array('required'=>true,'value'=>$card_basic_info['date_info']['datetype'],'class'=>'span12')))
	    -> addElement(new CDatetimepicker(CLanguage::Text('BASICINFO.startdate'), 'begin_timestamp', array(), array('placeholder'=>' ','data-format'=>'yyyy-MM-dd hh:mm:ss','value'=>$begin_timestamp)))
	    -> addElement(new CDatetimepicker(CLanguage::Text('BASICINFO.enddate'), 'end_timestamp', array('id'=>'datetimepicker2'), array('placeholder'=>' ','data-format'=>'yyyy-MM-dd hh:mm:ss','value'=>$end_timestamp)))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.fixed_term'),'fixed_term',array('placeholder'=>' ','required'=>true,'value'=>$this->data['fixed_term'],'class'=>'span12')))
	    -> addElement(new CTextbox(CLanguage::Text('BASICINFO.fixed_begin_term'),'fixed_begin_term',array('placeholder'=>' ','required'=>true,'value'=>$this->data['fixed_term'],'class'=>'span12')))
	    //-> addElement(new CDatetimepicker(CLanguage::Text('BASICINFO.end_time_stamp'), 'enddate', array('id'=>'datetimepicker3'), array('placeholder'=>' ','data-format'=>'yyyy-MM-dd hh:mm:ss','value'=>$session->data['shipping']['start_date'])))
	    -> addElement(new CTextarea(CLanguage::Text('BASICINFO.details'),'details',array('placeholder'=>' ','required'=>true,'value'=>nl2br($card_details[strtolower($card_details['card_type'])]['default_detail']),'class'=>'span12')))   
	    -> addElement(new CHidden('','logo_url',array('value'=>$logo)))
	    -> addElement(new CHidden('','card_type',array('value'=>'GENERAL_COUPON')))
	    -> addElement(new CHidden('','card_brand',array('value'=>base64_decode($this->router->get('brand')))))
	    -> addElement(new CHidden('','brand_id',array('value'=>base64_decode($this->router->get('brand_id')))))
	    -> addElement(new CHidden('','category_id',array('value'=>base64_decode($this->router->get('cat')))))
	    -> addElement(new CHidden('','activity_id',array('value'=>base64_decode($this->router->get('activity')))))
	    -> addElement(new CHidden('','custom_code',array('value'=>$this -> data['custom_code'])))
	    ;
	     
	    $card_info['fieldset']	= $form;
	     
	    array_push($this -> form_fieldsets, $card_info);
	    
	    //$this -> data['column2'] = '<img alt="230x230&amp;text=photo" src="'.$img.'">';
	}
	
	
	protected function success_committed_step()
	{
	    $resp_info = array(
	            'title'		      =>	CLanguage::Text('card_success_submit')				,
	            'subtitle'		  =>	CLanguage::Text('card_success_tips')				,
	            'icon'            =>    'smile'
	    );
	    
	    array_push($this -> form_fieldsets, $resp_info);
	}
	
	public function config_general_info()
	{
	    $router = $this -> load('router');
	
	    $this -> add_nav_path(CLanguage::Text('page_header_'), $router -> return_url('listview','brands'), 'sitemap');
	
	    $this -> data['page_header']	=	CLanguage::Text('MENU.WECHAT_CARD_CREAT');
	    $this -> data['page_icon']		=	'edit';
	    $this -> data['page_nav']		=	$this -> page_nav;
	
	
	}
	
}

?>