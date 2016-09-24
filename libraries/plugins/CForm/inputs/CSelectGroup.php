<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CSelectGroup extends CInput {
	
	protected $_prepend = false;
	protected $_append = false;
	
	protected $options = array();
	
	public function __construct($label, $name,array $options, array $properties = array()) {
	
		parent::__construct($label, $name,$properties);
	
		$this ->options = $options;
		
		if($properties['append']){
			$this ->_append = true;
		}
		else{
			//prepend by default
			$this ->_prepend = true;
		}
	
	}
	
	public function render($returnHTML = false)
	{
		if(!empty($this->_attributes['size']))
			$class = ' input-group-'.$this->_attributes['size'];
	
		echo '<div class="input-group '.$class.'">';
	
		if($this->_prepend == true)
		{
			echo $this -> get_addon($this->_properties['label']);
		}
	
		$select = new CSelect($this->_properties['label'],$this->_properties['name'],$this ->options, $this->_attributes);
		$select -> render();
	
		if($this ->_append == true)
		{
			echo $this -> get_addon($this->_properties['label']);
		}
	
		echo '</div>';
	}
	
	protected function get_addon($value)
	{
		$return = '<span class="input-group-addon"><i class="icon-ellipsis-vertical"></i> <strong>'.$value.'</strong></span>';
		return $return;
	}
}

?>