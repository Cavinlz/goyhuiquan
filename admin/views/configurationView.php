<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 * @author Cavinlz
 *
 */
class configurationView extends \FormView
{
    
	public function __construct(){
		parent::__construct();
		$this ->load_form();
	}
	
    public function display()
    {
    	$router = Console::getInstance('router');
    	/*
    	$form = array(
    			'target' => "frobj",
    			'action' => $router->return_url($router -> route_url['controller'],'save')
    	);
    
    	$this -> form = new CForm('config-form', $form);
    	*/
   
    	
    	parent::display();
    }
    
    
    public function config_general_info()
	{
		$this -> data['page_header']	=	array(CLanguage::Text('NAVMENU.CAT_MANAGE'),($this->data['is_update'] ? CLanguage::Text('CAT.EDIT_RACE') :CLanguage::Text('CAT.NEW_RACE')));
	}
    
    
    public function form_append($field, $field_vals)
    {
    	if(!is_object($this -> form)) return;
    	switch ($field['type'])
    	{
    		case 1:
    			$this -> form 
    				  -> addElement(new CTextarea(CLanguage::Text($field['config_label']),$field['name'], array('value'=>$field_vals[$field['name']])));
    		break;
    		case 2:
    			$this -> form
    				  -> addElement(new CSwitch(CLanguage::Text($field['config_label']), $field['name'], json_decode($field['properties'],true), array('host'=>$field_vals[$field['name']])));
    		break;
    		case 3:
    			$this -> form
    			-> addElement(new CSelect(CLanguage::Text($field['config_label']), $field['name'], json_decode($field['properties'],true), array('value'=>$field_vals[$field['name']])));
    		break;
    		default:
    			$this -> form
    				  -> addElement(new CTextbox(CLanguage::Text($field['config_label']),$field['name'], array('value'=>$field_vals[$field['name']])));
    	}
    }
}

?>