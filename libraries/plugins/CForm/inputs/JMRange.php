<?php

/** 
 * @author Cavinlz
 * 
 * Only available in H5
 * 
 */
class JMRange extends \CInput {
	
	protected $_attributes = array('type'=>"range", 'data-highlight'=>true);
	
	/**
	 */
	function __construct($label, $name, $properties = array()) 
	{
		if($properties['mini']){
			$this ->_attributes['data-mini'] = 'true';
			unset($properties['mini']);
		}
		
		if($properties)
			$this ->_attributes = array_merge($this ->_attributes, $properties);
		
		
		parent::__construct($label, $name,$this ->_attributes);
	}
	
	public function render($returnHTML = false) {
	
		parent::render($returnHTML);
	
	}
}

?>