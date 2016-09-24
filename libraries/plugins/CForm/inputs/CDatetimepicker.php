<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz 
 * 
 */
class CDatetimepicker extends \CInput {
	
	protected $_attributes = array("data-format" => "yyyy-MM-dd","class"=>"dtpicker");
	protected $_divproperties =  array('id'=>'datetimepicker1',"class"=>"input-append");
	protected $_iconproperties =  array('id'=>'datetimepicker1',"class"=>"btn btn-lg");
	
	public function __construct($label, $name,array $divproperties, array $properties = array(), $icon = array()) {
	
		parent::__construct($label, $name,$properties);

		$this ->_divproperties = array_merge($this ->_divproperties, $divproperties);
		
		if(isset($icon['class'])){
			$this->_iconproperties['class'] .= ' '.$icon['class'];
			unset($icon['class']);
		}
		
		$this ->_iconproperties = array_merge($this ->_iconproperties, $icon);
		
		if(isset($this->_iconproperties['type'])){
			$this->_iconproperties['class'] .= ' btn-'.$this->_iconproperties['type'];
			unset($this->_iconproperties['type']);
		}
		
		CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/bootstrap-datetimepicker.min.css');
	}
	
	public function render($returnHTML = false)
	{
		echo "<div ".$this->getElementAttributes($this ->_divproperties).">";
	
		$textbox = new CTextbox($this->_properties['label'], $this->_properties['name'],$this->_attributes);
		$textbox -> render();
	
		echo ' <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="btn btn-primary btn-lg icon-calendar"></i></span>';
		
		echo '</div>';
	}
	
	
	public function getElementAttributes($attributes) {
		$str = "";
		if(is_array($attributes)):
			foreach($attributes as $key => $attribute) {
	

				$str .= ' ' . $key;
				if($attribute !== "")
					$str .= '="' . $this->filter($attribute) . '"';
	
		
		}
		endif;
		return $str;
	}
	
}

?>