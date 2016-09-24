<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CAlertnotes extends \CInput {
	protected $class = 'alert alert-dismissable';
	
	function __construct($label, $type='warning',array $properties = array()) 
	{
		parent::__construct($label, '',$properties);
		
		$this->appendAttribute("class", $this->class);
		
		if(!empty($type)){
			$this->appendAttribute("class", 'alert-'.$type);
		}
	}
	
	public function render($returnHTML = false) 
	{
		echo "<div", $this->getAttributes(array('value')), ">";
		echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		echo '<strong>'.$this->label.'</strong>';
		echo '<p></p>';
		if(!empty($this->_attributes["value"]))
		{
			if(is_array($this->_attributes["value"]))
			{
				foreach ($this->_attributes["value"] as $val)
				{
					echo '<p>&nbsp;&nbsp;* '.$val.'</p>';
				}
			}
			else
				echo '<p>&nbsp;&nbsp;* '.$this->_attributes["value"].'</p>';
		}
			
		echo "</div>";
	}
}

?>