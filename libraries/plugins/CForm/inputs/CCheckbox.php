<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CCheckbox extends CSelect {
	protected $_attributes = array("type" => "checkbox");
	protected $is_mutiple = false;
	
	public function render($returnHTML = false) {
		if($returnHTML)
			ob_start();
		
		if(isset($this->_attributes["value"])) {
			if(!is_array($this->_attributes["value"]))
				$this->_attributes["value"] = array($this->_attributes["value"]);
				//print_r($this->_attributes["value"]);
		}
		else
			$this->_attributes["value"] = array();
		
		if(count($this->options) > 1){
			if(substr($this->_attributes["name"], -2) != "[]")
				$this->_attributes["name"] .= "[]";
			$this->is_mutiple = true;
		}
		
	
		$labelClass = $this->_attributes["type"];
		
		if($this->_attributes['class'])
			$labelClass .=' '.$this->_attributes['class'];
		$count = 0;
		foreach($this->options as $value => $text) {
	
			$id = ($this->is_mutiple)?$this->_attributes["id"].'-'.$count:$this->_attributes["id"];
			echo '<label class="', $labelClass, '"> <input id="', $id, '"', $this->getAttributes(array("id", "value", "checked", "required")), ' value="', $this->filter($value), '"';
			
			if(in_array($value, $this->_attributes["value"]))
				echo ' checked="checked"';
			
			echo '/> ', $text, ' </label> ';
			++$count;
		}
		
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
}

?>