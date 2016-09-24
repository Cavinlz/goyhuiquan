<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class JqmobileView extends \View {
	
	
	protected $properties = array();
	/**
	 * Form object, when needed
	 * @var unknown
	 */
	protected $form_fieldsets = null;
	
	
	
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
	
	
	public function get_pageid($return = false)
	{
		if($return)
			return $this -> pageid;
		
		echo $this -> pageid;
	}
	
	public function config_form_fieldsets()
	{
		
	}
	
	public function create_form_obj($id='jmform', $properties = array())
	{
		return new JMForm($id, $properties);
	}
	
	public function get_form_fieldsets()
	{
		return $this -> form_fieldsets;
	}
	
}

?>