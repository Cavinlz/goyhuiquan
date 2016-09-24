<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class loggController extends \Controller {
	
	public function indexOp()
	{
		
		$filepath = BasePath.DS.C('log_path').DS;
		
		if(file_exists($filepath))
		{
			//retrieve all the content of the log file into the array
			$data['data'] = array_reverse(file($filepath.'appl.log'));
			$data['dataerr'] = array_reverse(file($filepath.'system.log'));
		}
		
		$data['log_type'] = 'appl';
		
		$this ->Output('logging' , $data);
	}
	
}

?>