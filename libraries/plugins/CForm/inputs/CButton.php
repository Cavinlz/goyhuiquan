<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CButton extends CInput {
	protected $_attributes = array("type" => "button","class"=>"btn");
	protected $_icon = null;
	/**
	 * 
	 * @param string $label  Button's Value
	 * @param string $type  Button's Type as well as the relevant class name
	 * @param array $properties Extra attributes for the button
	 */
	public function __construct($label = "Submit", $name="", $type = "", array $properties = null) {
		if(!is_array($properties))
			$properties = array();
	
		//detemine the button's size
		if(!empty($properties["size"])){
			$class = ' btn-'.$properties['size'];
			unset($properties["size"]);
		}
		
		if(empty($type)){
			$class .= " btn-default";
		}
		elseif($type == "submit"){
			$class .= " btn-primary";
			$this->_attributes['type'] = 'submit';
		}
		elseif($type == 'reset'){
			$this->_attributes["type"] = $type;
			$class .= " btn-default";
		}
		elseif($type != "cancel"){ //cancel & reset has no style which is the default one
			$class .= " btn-".$type;
		}
		
		$this->_attributes["class"] .= $class;
		if(!empty($properties["class"])){
			$this->_attributes["class"] .= " " . $properties["class"];
			unset($properties["class"]);
		}

		if(empty($properties["value"]))
			$properties["value"] = $label;
		
		if(!empty($properties["icon"])){
			$this->_icon = $properties["icon"];
			unset($properties["icon"]);
		}
		
		
		
		parent::__construct($label, $name,$properties);
		
	}

	public function render($returnHTML = false) {
		if($returnHTML)
			ob_start();
		
		if($this ->_icon != null){
			$icon = "<span class='fa fa-".$this ->_icon."'></span>&nbsp;&nbsp;";
		}
		
		//echo ' <div class="input-group">';
		echo '<button', $this->getAttributes(array('value')), '>'.$icon.$this->_attributes['value'].'</button>';
		//echo '</div>';
		
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
}

?>