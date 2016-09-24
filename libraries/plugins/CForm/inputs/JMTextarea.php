<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class JMTextarea extends \CTextarea {
	
	protected $class = null;
	
	/**
	 */
	public function __construct($label, $name, array $properties) {
		parent::__construct ( $label, $name, $properties );
	}
}

?>