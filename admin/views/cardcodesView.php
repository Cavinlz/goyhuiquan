<?php

/** 
 * @author Administrator
 * 
 */
class cardcodesView extends \FormView
{

    /**
     */
    public function config_form_fieldsets()
	{
	    
	    switch($this -> data['currentstep'])
	    {
	        case '2':
	            $this -> data['noShowBtn'] = true;
	            $this -> config_second_step_fieldset();
	            break;
	        case '3':
	            if($this -> router -> get('rs') == 'success')
	               $this -> success_committed_step();
	            break;
	        default:
	            $this -> data['noShowBtn'] = false;
	            $this -> config_first_step_fieldset();
	    }
	    
	    $this -> data['submit_button_val'] = CLanguage::Text('BUTTON.IMPORT');
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
	             
	            'title'		      =>	CLanguage::Text('import_ways')				,
	            'subtitle'		  =>	CLanguage::Text('import_codes_tips')				,
	            'icon'            =>    'cloud-upload'
	  
	    );

	    $this -> data['formaction'] = $this -> router -> return_url('wechatcards','uploadlogo');
	    
	    $form = $this ->create_nonform_obj();
	    	
	    $bigbtn = '<div class="span3 box-quick-link blue-background">
                    <a>
                        <div class="header">
                            <div class="icon-upload-alt"></div>
                        </div>
                        <div class="content">'.CLanguage::Text('import_file').'</div>
                    </a>
                </div>
                                <div class="span3 box-quick-link orange-background">
                    <a href="'.$this-> router->return_url('cardcodes','import/'.$this->data['cardid'].'/',array('step'=>2,'w'=>'manual')).'">
                        <div class="header">
                            <div class="icon-edit"></div>
                        </div>
                        <div class="content">'.CLanguage::Text('import_manual').'</div>
                    </a>
                </div>
                                <div class="span3 box-quick-link red-background">
                    <a>
                        <div class="header">
                            <div class="icon-exchange"></div>
                        </div>
                        <div class="content">'.CLanguage::Text('import_from_api').'</div>
                    </a>
                </div>
                    ';
	    
	    $form  -> addElement(new CHTML($bigbtn))
	           ;
	    
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
	    
	            'title'		      =>	CLanguage::Text('import_manual')				,
	            'subtitle'		  =>	CLanguage::Text('manual_write_tips')				,
	            'icon'            =>    'edit'
	    
	    );
	     
	    $this -> data['formaction'] = $this -> router -> return_url('wechatcards','createcard');
	    
	    $logo = base64_decode($this->router->get('lg'));
	    
	    $form = $this ->create_nonform_obj();
	     
	    $form  
	    -> addElement(new CTextarea(CLanguage::Text('manual_write'),'details',array('required'=>true,'value'=>$this->data['details'],'class'=>'span12')))   
	    -> addElement(new CHidden('','card_brand',array('value'=>base64_decode($this->router->get['brand']))))
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
	
	    $this -> data['page_header']	=	CLanguage::Text('import_codes');
	    $this -> data['page_icon']		=	'barcode';
	    $this -> data['page_nav']		=	$this -> page_nav;
	
	
	}
	
}

?>