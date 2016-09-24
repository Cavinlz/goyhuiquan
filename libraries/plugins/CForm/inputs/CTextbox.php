<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CTextbox extends CInput {
	protected $_attributes = array("type" => "text","class"=>"form-control");
	
	public function __construct($label, $name,array $properties = array()){

		if(isset($properties['class'])){
			$this->_attributes['class'] .= ' '.$properties['class'];
			unset($properties['class']);
		}
		
		parent::__construct($label, $name,$properties);
		
		if(empty($properties['placeholder']))
			$this->_attributes['placeholder'] = $label;
		
	}
	
	public function render($returnHTML = false) {
		$addons = array();
		if(!empty($this->prepend))
			$addons[] = "input-prepend";
		if(!empty($this->append))
			$addons[] = "input-append";
		if(!empty($addons))
			echo '<div class="', implode(" ", $addons), '">';
	
		$this->renderAddOn("prepend");
		parent::render();
		$this->renderAddOn("append");
	
		if(!empty($addons))
			echo '</div>';
	}
	
	protected function renderAddOn($type = "prepend") {
		if(!empty($this->$type)) {
			$span = true;
			if(strpos($this->$type, "<button") !== false)
				$span = false;
	
			if($span)
				echo '<span class="add-on">';
	
			echo $this->$type;
	
			if($span)
				echo '</span>';
		}
	}
}

?>