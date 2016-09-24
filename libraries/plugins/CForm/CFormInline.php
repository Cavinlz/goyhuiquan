<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CFormInline extends CForm {
	/*
	 * @Notes: the form-inline can only be used in the big screen (>768px)
	 */
	protected $class = "form-inline";
	
	
	function __construct($id, array $properties = array())
	{
		parent::__construct($id, $properties);
	}
	
	public function render() {
		$this->appendAttribute("class", $this->class);
	
		echo '<form', $this->getAttributes(), '>';
		
	
		$elements = $this->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			if($e > 0)
				echo ' ';
			echo '<div class="form-group" style="display: inline-table;vertical-align: middle;">';
			$element = $elements[$e];
			echo $element->render();
			echo '</div>';
			++$elementCount;
		}
	
		echo '</form>';
		unset($this->_elements);
	}
}

?>