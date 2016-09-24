<?php

/** 
 * @author Cavinlz
 * 
 */
class JMSelect extends \CSelect {
	
	protected $_attributes = array();
	/**
	 *
	 * @param
	 *        	$label
	 *        	
	 * @param
	 *        	$name
	 *        	
	 * @param
	 *        	$options
	 *        	
	 * @param array $properties        	
	 *
	 */
	public function __construct($label, $name, $options, array $properties) {
		parent::__construct ( $label, $name, $options, $properties );
	}
}

?>