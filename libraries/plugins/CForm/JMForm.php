<?php

/** 
 * @author Cavinlz
 * 
 * @desp Form for Jquery Mobile
 */
class JMForm extends \CForm {
	
	/**
	 */
	function __construct($id, $properties = array()) {
		
		$this ->_attributes['id'] = $this ->_attributes['name'] = $id;
		
		$this ->_attributes = array_merge($this ->_attributes,$properties);
		
	}
	
	public function render() {
		$this->appendAttribute("class", $this->class);
	
		echo '<form', $this->getAttributes(), '>';
	
		$elements = $this->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];
	
			if($element instanceof CHidden)
				$element->render();
			elseif($element instanceof JMButton) {
				if($e == 0 || !$elements[($e - 1)] instanceof JMButton)
					echo '<div class="form-actions">', $this->renderLabel($element);
	
				echo ' &nbsp;';
				$element->render();
	
				if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof CButton)
					echo '</div>';
			}
			else {
				echo '<div data-role="fieldcontain">', $this->renderLabel($element), $element->render() ,'</div>';
				++$elementCount;
			}
		}
	
		echo '</form>';
	
		unset($this->_elements);
	}
	
	protected function renderLabel($element) {
	
		$label = ($element instanceof CButton)?'':$element->getLabel();
		echo '<label for="', $element->getAttribute("id"), '">';
		/*
		if($element->isRequired())
			echo '<span class="required" style="color:red;">* </span>';
			*/
		echo $label, '</label>';
	}
}

?>