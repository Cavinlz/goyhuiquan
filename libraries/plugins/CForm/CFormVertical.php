<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CFormVertical extends \CForm {
	protected $class = 'form-profile';
	
	
	
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
			elseif($element instanceof CButton) {
				if($e == 0 || !$elements[($e - 1)] instanceof CButton)
					echo '<div class="form-group">';
	
				echo ' &nbsp;';
				$element->render();
	
				if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof CButton)
					echo '</div>';
			}
			else {
				echo '<div class="form-group ">', $element->render() ,'</div>';
				++$elementCount;
			}
		}
	
		echo '</form>';
	
		unset($this->_elements);
	}
}

?>