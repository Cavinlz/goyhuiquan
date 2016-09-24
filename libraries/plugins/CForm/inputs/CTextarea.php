<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CTextarea extends CInput {
	protected $_attributes = array("rows" => "3");
	protected $class = 'form-control';
	/**
	 */
	function __construct($label, $name,array $properties = array()) 
	{
		parent::__construct($label, $name,$properties);
		if(empty($properties['placeholder']))
			$this->_attributes['placeholder'] = $label;
	}
	
	public function render($returnHTML = false) 
	{
		$this->appendAttribute("class", $this->class);
		echo "<textarea", $this->getAttributes(array("value")), ">";
		if(!empty($this->_attributes["value"]))
			echo $this->filter($this->_attributes["value"]);
		echo "</textarea>";
	}
}

?>