<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 *
 * @author Cavinlz
 *        
 */
class CForm extends Form{
	protected $_configuration = array('method'=>'post','role'=>'form');
	protected $_elements = array();
	protected $class = "form-horizontal";
	protected $left_class = null;
	protected $right_class = null;
	/**
	 */
	function __construct($id, array $properties = array()) 
	{

			//detemine the correspondant size as well as the cols (md-12 ,sm-6)
			if(isset($properties['size'])){
				$this -> class .= ' col-'.$properties['size'];
			}
			else {
				//$this -> class .= ' col-md-12';
			}
		
			$this ->_attributes['id'] = $this ->_attributes['name'] = $id;
			
			$this ->_attributes['role'] = 'form';
			
			$this ->_attributes['method'] = 'post';
			
			$this ->_attributes = array_merge($this ->_attributes,$properties);
		

	}
	
	public function addElement(CInput $element) {
		//$element->_setForm($this);
	
		//If the element doesn't have a specified id, a generic identifier is applied.
		$id = $element->getAttribute("id");
		if(empty($id))
			$element->setAttribute("id", $this->_attributes["id"] . "-element-" . sizeof($this->_elements));
		$this->_elements[] = $element;
		return $this;
	}
	
	public function getElements() {
		return $this->_elements;
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
			elseif($element instanceof CButton) {
				if($e == 0 || !$elements[($e - 1)] instanceof CButton)
					echo '<div class="form-actions">', $this->renderLabel($element);
				
				echo ' &nbsp;';
				$element->render();
	
				if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof CButton)
					echo '</div>';
			}
			else {
				echo '<div class="form-group ">', $this->renderLabel($element), '<div class="controls '.$this->right_class.'">', $element->render() ,'</div></div>';
				++$elementCount;
			}
		}
	
		echo '</form>';
		
		unset($this->_elements);
	}
	
	protected function renderLabel($element) {
		
		if($element instanceof CButton)return;
		$label = $element->getLabel();
		
		if(empty($label)) return;
			echo '<label class="control-label '.$this->left_class.'" for="', $element->getAttribute("id"), '">';
			if($element->isRequired())
				echo '<span class="required" style="color:red;font-weight:bold">* </span>';
			echo $label, '</label>';
	}
	
	public function setSideWidth($left, $right, $media='sm')
	{
		$this->left_class .= ' col-'.$media.'-'.$left;
		$this->right_class .= ' col-'.$media.'-'.$right;
		return $this;
	}
	
}

?>