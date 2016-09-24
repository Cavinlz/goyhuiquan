<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class CFile extends \CTextbox {
	
	protected $_attributes = array("type" => "file","class"=>"file","data-preview-file-type"=>"text");
	
	public function __construct($label, $name,array $properties = array()){
	
		parent::__construct($label, $name,$properties);
		
	
	}
	
}

?>