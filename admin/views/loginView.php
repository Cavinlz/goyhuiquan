<?php

/** 
 * @author Cavinlz
 * 
 */
class loginView extends View {
	
	/**
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	protected function configSystemTemplates()
	{
	   
		$this ->template_default = array(
				'body'=> Template_Path.DS.'login'
		);
	}
}

?>