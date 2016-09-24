<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CSpanlabel extends \CInput {
	protected $class = "badge";
	/**
	 */
	function __construct($label, $type='',array $properties = array()) 
	{
		parent::__construct($label, '',$properties);
		$this->appendAttribute("class", $this->class);
		
		if(!empty($type)){
			$this->appendAttribute("class", 'badge-'.$type);
		}
		
	}
	
	public function render($returnHTML = false) 
	{
		
		$html = "<span". $this->getAttributes(). " style='font-size:90%'>";
		$html .= $this->filter($this->label);
		$html .= "</span>";
		

		if($returnHTML) {
			return $html;
		}
		else
			echo $html;
	}
}

?>