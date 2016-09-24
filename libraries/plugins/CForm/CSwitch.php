<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 * @author Cavinlz
 *
 */
class CSwitch extends \CInput
{
    protected $_attributes = array("class"=>"switch");
    protected $_chkattributes = array("type" => "checkbox");
    /**
     */
    function __construct ($label, $name,  array $properties , array $chk_properties)
    {
        //detemine the button's size
        if(!empty($properties["size"])){
        	$class = ' switch-'.$properties['size'];
        	unset($properties["size"]);
        }
        
        if(!empty($properties["class"])){
            $this ->_attributes['class'] .= " ".$properties["class"]; 
            unset($properties["class"]);
        }
        
        $this ->_chkattributes = array_merge($this ->_chkattributes,$chk_properties);
        $this -> _chkattributes['name'] = $name;
        if(empty($this -> _chkattributes['value']))
         $this -> _chkattributes['value'] = 1;
        
        parent::__construct($label, $name,$properties);
    }
    
    public function render($returnHTML = false) {
    	if($returnHTML)
    		ob_start();

    	
        echo "<div ", $this->getAttributes(array('value','name')), ">";
    	echo '<input', $this->getChkboxAttributes(), ' />';
    	echo '</div>';
    
    	if($returnHTML) {
    		$html = ob_get_contents();
    		ob_end_clean();
    		return $html;
    	}
    }
    
    public function getChkboxAttributes(array $ignore = array()) {
    	$str = "";
    	if($ignore) $prechk = true;
    	if(!empty($this->_chkattributes)) {
    		foreach($this->_chkattributes as $key => $attribute) {
    		    if($prechk && in_array($key, $ignore)){
    		    	continue;
    		    }
    		    $str .= ' ' . $key;
    		    if($attribute !== "")
    		    	$str .= '="' . $this->filter($attribute) . '"';
    		}
    	}
    	return $str;
    }
}

?>