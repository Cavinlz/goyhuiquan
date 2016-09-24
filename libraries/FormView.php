<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class FormView extends \View {
	
	protected $form_fieldsets = array();
	protected $page_nav = null;
	
	public function init($template ='', $config = array())
	{
		$this -> load_form();
		
		parent::init($template , $config);
		
	}
	
	public function display()
	{
		$this -> config_form_fieldsets();
		parent::display();
	}

	public function get_form_fieldsets()
	{
		return $this -> form_fieldsets;
	}
	
	public function create_nonform_obj()
	{
		return new NForm();
	}
	
	
	public function config_general_info()
	{
		$this -> data['page_header']	=	CLanguage::Text('page_header');
	}
	
	public function config_form_fieldsets()
	{
		$this -> config_general_info();
	}
	
	public function add_nav_path($text, $url, $icon)
	{
	    $this -> page_nav .= "<li><a href='$url'><i class='icon-$icon'></i> $text</a></li> ";
	    $this -> page_nav .= "<li class='separator'><i class='icon-angle-right'></i></li>";
	}
	
}

?>