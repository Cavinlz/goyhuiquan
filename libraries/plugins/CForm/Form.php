<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
abstract class Form {
	protected $_attributes = array();
	
	protected $ignoreAttr = array('size','icon');
	
	public function getAttributes(array $ignore = array()) {
		$str = "";
		if($ignore) $prechk = true;
		//print_r($this->_attributes);
		if(!empty($this->_attributes)) {
			foreach($this->_attributes as $key => $attribute) {
				
				if(in_array($key, $this->ignoreAttr)) continue;
				
				if($prechk && in_array($key, $ignore)){
					continue;
				}
				
				if($key == 'required')
				{
					$str .= ' required ';
				}
				else
				{
					$str .= ' ' . $key;
					if($attribute !== "")
						$str .= '="' . $this->filter($attribute) . '"';
				}
				
				
			}
		}
		return $str;
	}
	
	public function appendAttribute($attribute, $value) {
		if(isset($this->_attributes)) {
			if(!empty($this->_attributes[$attribute]))
				$this->_attributes[$attribute] .= " " . $value;
			else
				$this->_attributes[$attribute] = $value;
		}
	}
	
	public function setAttribute($attribute, $value) {
		if(isset($this->_attributes))
			$this->_attributes[$attribute] = $value;
	}
	
	/*This method prevents double/single quotes in html attributes from breaking the markup.*/
	protected function filter($str) {
		return htmlspecialchars($str);
	}
	
	public function getAttribute($attribute) {
		$value = "";
		if(isset($this->_attributes[$attribute]))
			$value =  $this->_attributes[$attribute];
	
		return $value;
	}
	
	
}


/*
 * Configure to be Autoload requested classes at runtime.
 * 
 * */
function Load($class) {
	$file = __DIR__ . "/" . str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";

	if(is_file($file))
		include_once $file;
	else{
		$file = __DIR__ . "/inputs/" . str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
		if(is_file($file))
			include_once $file;
	}
}
spl_autoload_register("Load");
if(in_array("__autoload", spl_autoload_functions()))
	spl_autoload_register("__autoload");


?>