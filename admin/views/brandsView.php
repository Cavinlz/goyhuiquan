<?php
/**
 *
 * @author Cavinlz
 *        
 */
class brandsView extends \FormView
{
    
    public function config_form_fieldsets()
    {
        $form = $this ->create_nonform_obj();
        
        $form   -> addElement(new CTextbox(CLanguage::Text('brand_name'),'brand_name',array('placeholder'=>' ','required'=>true,'value'=>$this->data['brand_name'],'class'=>'span12')));
        
        if($this -> data['state'] == 'Y')
        {
            $logo_info = array(
            
                    'title'		      =>	CLanguage::Text('brand_info')				,
                    'subtitle'		  =>	CLanguage::Text('brand_info_auth_tips')				,
                    'icon'            =>    'map-marker'
            
            );
            
            $form   -> addElement(new CTextbox(CLanguage::Text('brand_id'),'brand_id',array('placeholder'=>' ','required'=>true,'value'=>$this->data['brand_id'],'class'=>'span12')))
            -> addElement(new CFile(CLanguage::Text('brand_logo'),'prodimg', array('requried'=>true)))
            -> addElement(new CDatetimepicker(CLanguage::Text('expired_date'), 'expired_date', array(), array('placeholder'=>' ','required'=>true,'data-format'=>'yyyy-MM-dd','value'=>'')))
            -> addElement(new CHidden('','auth',array('value'=>$this->data['state'])))
            -> addElement(new CHidden('','k',array('value'=>$this->data['id'])));
        }
        else
        {
            $logo_info = array(
            
                    'title'		      =>	CLanguage::Text('brand_info')				,
                    'subtitle'		  =>	CLanguage::Text('brand_info_tips')				,
                    'icon'            =>    'map-marker'
            
            );
             
            
            $form   
            -> addElement(new CFile(CLanguage::Text('brand_logo'),'prodimg', array('requried'=>true)))
            -> addElement(new CHidden('','init_img',array('value'=>$this->data['mac_pic'])))
            -> addElement(new CHidden('','k',array('value'=>$this->data['id'])));
        }
        
        $this -> data['formaction'] = $this -> router -> return_url('brands','createbrand');
        $logo_info['fieldset']	= $form;
         
        array_push($this -> form_fieldsets, $logo_info);
        
        parent::config_form_fieldsets();
    }
    
    public function config_general_info()
    {
        $router = $this -> load('router');
    
        $this -> add_nav_path(CLanguage::Text('page_header_'), $router -> return_url('listview','brands'), 'sitemap');
    
        $this -> data['page_header']	=	CLanguage::Text('MENU.BRAND_CREATE');
        $this -> data['page_icon']		=	'edit';
        $this -> data['page_nav']		=	$this -> page_nav;
        $this -> data['jsmodel']       =   'brand';
    
    }
    
    
}

?>