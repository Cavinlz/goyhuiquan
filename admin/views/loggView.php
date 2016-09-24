<?php
if (defined("CZCool") or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class loggView extends \FormView {
	
	
	public function get_event_session($msg, $log = 'appl')
	{
		return $msg[1];
	} 
	
	public function get_event_url($msg, $log = 'appl')
	{
		return $log == 'appl' ? $msg[3]:$msg[2];
	}
	
	public function get_event_msg($msg, $log = 'appl')
	{
		return $log == 'appl' ?  strip_tags($msg[4]) :  strip_tags($msg[3]);
	}
	
	public function get_event_type($msg, $log = 'appl')
	{
	    return $log == 'appl' ? $msg[2] : $msg[1];
	}
	
    public function get_module_name()
    {
    	return CLanguage::Text('MENU.SYSTEM_LOG');
    }
    
    public function config_general_info()
    {
    	$router = $this -> load('router');
    
    	
    
    		$this -> data['page_header']	=	CLanguage::Text('page_header');
    		$this -> data['page_icon']		=	'warning-sign ';
    		$this -> data['page_nav']		=	$this -> page_nav;
    		$this -> data['formaction']		=   $router -> return_url('translators','new');
    
    }
    
	
}

?>