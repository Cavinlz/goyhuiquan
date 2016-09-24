<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CHidden extends CTextbox {
	protected $_attributes = array("type" => "hidden","class"=>"form-control");
	
	public function __construct($label, $name, $properties = array()){
	
		parent::__construct($label, $name,$properties);
	
		if(empty($properties['placeholder']))
			$this->_attributes['placeholder'] = $label;
	
	}
}

?>