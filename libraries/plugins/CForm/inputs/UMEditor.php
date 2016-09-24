<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class UMEditor extends CInput {
	
	protected $skipJS = true;
	//define the js files path here
	protected $JSpath = '';
	
	
	function __construct($label, $name, array $properties = array()){
		parent::__construct($label, $name,$properties);
		
		if($this ->_attributes['js'] == false){
			$this->skipJS = false;
		}
		
		
	}
	
	public function render($returnHTML = false) {
		echo "<script", $this->getAttributes(array("value", "required")), " type=\"text/plain\" ></script>";
		echo $this->rendScripts();
	}
	
	public function getJSFiles() {
		return array(
				$this->JSpath . "/umeditor.mini/umeditor.config.js",
				$this->JSpath . "/umeditor.mini/umeditor.min.js"
		);
	}
	
	protected function rendScripts()
	{
		if(!$this->skipJS) {
			$value = $this->_attributes['value'];
			$editor = $this->_attributes['id'];
			echo <<<JS
			<script>
			UM.getEditor('$editor',{
            	toolbar:['undo redo | bold underline | justifyleft justifycenter justifyright justifyjustify']
            }).setContent('$value');</script>
JS;
		}
	}
	
}

?>