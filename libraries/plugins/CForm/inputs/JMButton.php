<?php

/** 
 * @author Cavinlz
 * 
 */
class JMButton extends \CInput {
	
	/**
	 */
	function __construct($label,$name, $properties = array()) {
		parent::__construct($label, $name,$properties);
		
	}
	
	public function render($returnHTML = false) {
		if($returnHTML)
			ob_start();
	
		
		echo '<a data-role="button" href="#"', $this->getAttributes(array('value')), '>'.$this->_attributes['value'].'</a>';
		//echo '</div>';
	
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
}

?>