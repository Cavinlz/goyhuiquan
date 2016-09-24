<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 * @tutorial Such Model can enable the textbox to be combined with the addon feature
 */
class CTextboxGroup extends CInput {
	
	protected $_attributes = array();
	
	protected $_prepend = false;
	protected $_append = false;
	
	protected $_preatrributes = array();
	protected $_appatrributes = array();
	
	protected $_pretype = null;
	protected $_apptype = null;
	
	
	function __construct($label, $name, array $properties = array(), $preproperties = array(),$appproperties = array()) {
		
		parent::__construct($label, $name,$properties);
		
		if($preproperties){
			$this ->_prepend = true;
			
			if(!empty($preproperties['type'])){
				$this->_pretype = $preproperties['type'];
				unset($preproperties['type']);
			}
			
			$this ->_preatrributes = array_merge($this ->_preatrributes,$preproperties);
			
		}
		
		if($appproperties){
			$this ->_append = true;
			
			if(!empty($appproperties['type'])){
				$this->_apptype = $appproperties['type'];
				unset($appproperties['type']);
			}
			
			$this ->_appatrributes = array_merge($this ->_appatrributes,$appproperties);
		}
	
		
	}
	
	
	public function render($returnHTML = false) 
	{
		if(!empty($this->_attributes['size']))
			$class = ' input-group-'.$this->_attributes['size'];
		
		echo '<div class="input-group '.$class.'">';
		
		if($this->_prepend == true)
		{
			echo $this -> get_addon($this->_pretype, $this->_preatrributes);
		}
		
		$textbox = new CTextbox($this->_properties['label'],$this->_properties['name'],$this->_attributes);
		$textbox -> render();
		
		if($this ->_append == true)
		{
			
			echo $this -> get_addon($this->_apptype, $this->_appatrributes);
		}
		
		echo '</div>';
	}
	
	protected function get_addon($type, $properties)
	{
		if(!empty($properties['icon'])){
			$icon =  $this ->renderIcon($properties['icon']);
		}
		
		if(!empty($properties['class'])){
			$class = $properties['class'];
		}
		
		switch($type)
		{
			case 'button':
				$newbtn = new CButton($properties['label'],$properties['name'],'submit',$properties);
				$return = '<span class="input-group-btn '.$class.'">'.$icon.$newbtn->render(true).'</span>';
				break;
			default:
				$return = '<span class="input-group-addon '.$class.'">'.$icon.'<strong>'.$properties['value'].'</strong></span>';
		}
		
		return $return;
	}
	
	
	
	
}

?>