<?php

/** 
 * @author Cavinlz
 * 
 */
class JMTextbox extends CInput {
	
	/**
	 */
	public function __construct($label, $name, array $properties) {
		parent::__construct ( $label, $name, $properties );
		if(empty($properties['placeholder']))
			$this->_attributes['placeholder'] = $label;
	}
	
	public function render($returnHTML = false) {
		
		parent::render($returnHTML);
		
	}
	
}

?>