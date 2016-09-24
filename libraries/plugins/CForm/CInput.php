<?php
if (defined("CZCool") or die( "Access Denied!" ));

/**
 *
 * @author Cavinlz
 *        
 */
abstract class CInput extends Form{
	
	protected $_properties = array();
	protected $is_required = false;

	/**
	 */
	function __construct($label, $name, array $properties = array()) 
	{
		$this ->_properties = array(
			'label' => $label,
			'name'=>$name
		);
		
		$this->label = $label;
		
		$this ->_attributes['name'] = $name;
		$this ->_attributes = array_merge($this ->_attributes,$properties);
		
		if(empty($this->_attributes['id'])){
			$this->_attributes['id'] = $name;
		}
		
		if(isset($properties['required'])){
			$this ->is_required = true;
		}
		
	}
	
	public function render($returnHTML = false) {
		if($returnHTML)
			ob_start();
		echo '<input', $this->getAttributes(),  '/>';
		
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
	
	
	public function getLabel() {
		return $this->label;
	}
	
	public function isRequired()
	{
		return $this ->is_required;
	}
	
	public function renderIcon($name)
	{
		return '<i class="icon-'.$name.'"></i><span class="glyphicon glyphicon-'.$name.'"></span>&nbsp;';
	}
	
}

?>