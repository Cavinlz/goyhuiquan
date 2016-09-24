<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CHTML extends CInput {
	
	/**
	 */
	function __construct($value) {
		$properties = array("value" => $value);
		parent::__construct("", "", $properties);
	}
	
	public function render($returnHTML = false) {
		echo $this->_attributes["value"];
	}
}

?>