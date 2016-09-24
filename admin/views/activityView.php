<?php

/**
 *
 * @author Administrator
 *        
 */
class activityView extends \FormView
{

    public function config_form_fieldsets()
    {
        $logo_info = array(
        
                'title'		      =>	CLanguage::Text('act_info')				,
                'subtitle'		  =>	CLanguage::Text('act_info_tips')				,
                'icon'            =>    'gift'
	 
        );
         
        $this -> data['formaction'] = $this -> router -> return_url('activities','createact');
         
        $form = $this ->create_nonform_obj();
        
        $form   -> addElement(new CTextbox(CLanguage::Text('act_name'),'act_name',array('placeholder'=>' ','required'=>true,'value'=>$this->data['act_name'],'class'=>'span12')))
                -> addElement(new CDatetimepicker(CLanguage::Text('act_startdate'), 'begin_timestamp', array(), array('placeholder'=>' ','data-format'=>'yyyy-MM-dd','required'=>true,'value'=>$session->data['shipping']['start_date'])))
                -> addElement(new CDatetimepicker(CLanguage::Text('act_enddate'), 'end_timestamp', array('id'=>'datetimepicker2'), array('placeholder'=>' ','required'=>true,'data-format'=>'yyyy-MM-dd','value'=>$session->data['shipping']['start_date'])))
                -> addElement(new CTextarea(CLanguage::Text('act_details'),'description',array('placeholder'=>' ','required'=>true,'value'=>$this->data['description'],'class'=>'span12')))
                -> addElement(new CHidden('','k',array('value'=>$this->data['id'])));
         
        $logo_info['fieldset']	= $form;
         
        array_push($this -> form_fieldsets, $logo_info);
        
        parent::config_form_fieldsets();
    }
    
    public function config_general_info()
    {
        $router = $this -> load('router');
    
        $this -> data['page_header']	=	CLanguage::Text('MENU.ACT_CREATE');
        $this -> data['page_icon']		=	'edit';
        $this -> data['page_nav']		=	$this -> page_nav;
        $this -> data['jsmodel']       =   'activity';
    
    }
    
}

?>