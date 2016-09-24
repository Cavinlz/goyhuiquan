<?php

/** 
 * @author Cavinlz
 * 
 */
class NForm extends \CForm {
	
	/**
	 * The elements will not be included in the form
	 *  
	 */
	function __construct($id = '')
	{
	
		parent::__construct(empty($id)?'noform':$id, array());
	
	}
	
	public function render() {
		$this->appendAttribute("class", $this->class);
	
		//echo '<form', $this->getAttributes(), '><fieldset>';
	
		$elements = $this->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];
	
			if($element instanceof CHidden)
				$element->render();
			elseif($element instanceof CHTML)
				$element->render();
			elseif($element instanceof CButton) {
				if($e == 0 || !$elements[($e - 1)] instanceof CButton)
					echo '<div class="form-actions">', $this->renderLabel($element);
	
				echo ' &nbsp;';
				$element->render();
	
				if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof CButton)
					echo '</div>';
			}
			else {
				echo '<div class="control-group ">', $this->renderLabel($element), '<div class="controls '.$this->right_class.'">', $element->render() ,'</div></div>';
				++$elementCount;
			}
		}
	
		//echo '</fieldset></form>';
	
		unset($this->_elements);
	}
	
	
	
}

?>