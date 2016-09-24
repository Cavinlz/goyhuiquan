<?php

/** 
 * @author Cavinlz
 * 
 * Radio form in Jquery Mobile
 * 
 */
class JMRadio extends \CInput {
	
	protected $_chkattributes = array("type" => "radio");
	protected $options;
	/**
	 */
	function __construct($label, $name,  $options = array() , array $chk_properties = null) 
	{
		$properties = array();
		
		if(isset($chk_properties['datatype'])){
			$properties['data-type'] = $chk_properties['datatype'];
			unset($chk_properties['ishorizontal']);
		}
		else{
			$properties['data-type'] = "horizontal";
		}
		
		if(isset($chk_properties['required'])){
			$properties['required'] = $chk_properties['required'];
			unset($chk_properties['required']);
		}
		
		$this->options = $options;
		
		if(isset($chk_properties['mini'])){
			$properties['data-mini'] = "true";
			unset($chk_properties['mini']);
		}
		
		$this ->_chkattributes = array_merge($this ->_chkattributes,$chk_properties);
		$this -> _chkattributes['name'] = $name;
		
		
		
		parent::__construct($label, $name,$properties);
	}
	
	public function render($returnHTML = false) {
		if($returnHTML)
			ob_start();
	
		echo "<fieldset data-role ='controlgroup' ", $this->getAttributes(array('value','name','id')), ">";
		//echo "<legend>",$this->label,"</legend>";
		$count = 0;
		foreach($this->options as $value => $text) {
		
			$id = $this->_attributes["id"].'-'.$count;
			echo  '<input id="', $id, '"', $this->getRadioAttributes(array("id", "value", "checked")), ' value="', $this->filter($value), '"';
			if($value==$this->_chkattributes["value"])
				echo ' checked="checked"';
			echo '/> ';
			echo '<label for="', $id, '" style="min-width:70px;text-align:center">', $text, ' </label> ';
			++$count;
		}
		
		echo '</fieldset>';
	
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
	
	public function getRadioAttributes(array $ignore = array()) {
		$str = "";
		if($ignore) $prechk = true;
		if(!empty($this->_chkattributes)) {
			foreach($this->_chkattributes as $key => $attribute) {
				if($prechk && in_array($key, $ignore)){
					continue;
				}
				$str .= ' ' . $key;
				if($attribute !== "")
					$str .= '="' . $this->filter($attribute) . '"';
			}
		}
		return $str;
	}
}

?>