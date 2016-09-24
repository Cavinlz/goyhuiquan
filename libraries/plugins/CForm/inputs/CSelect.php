<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CSelect extends CInput {
	
	protected $_attributes = array("class"=>"form-control");
	protected $options;
	
	public function __construct($label, $name,  $options = array() ,array $properties = array())
	{
		$this->options = $options;
		
		if(isset($properties['class'])){
			$this->_attributes['class'] .= ' '.$properties['class'];
			unset($properties['class']);
		}
		
		parent::__construct($label, $name,$properties);
	}
	
	public function render($returnHTML = false)
	{
		if($returnHTML)
			ob_start();
		echo '<select', $this->getAttributes(), '>';
		
		foreach ($this->options as $key => $name)
		{
			$select = ($key == $this->_attributes['value'])? 'selected':'';
			echo '<option value="'.$key.'" '.$select.'>'.$name.'</options>';
		}
		
		echo '</select>';
		
		if($returnHTML) {
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	}
}

?>