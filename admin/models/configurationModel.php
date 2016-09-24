<?php
if (defined("CZCool") or die( "Access Denied!" ));
/**
 * @author Cavinlz
 *
 */
class configurationModel extends \Model
{
    
    public function __construct()
    {
    	parent::__construct();
    	$this -> setTable('system_config');
    }
    
    public function save_system_config($config, $category)
    {
        
        $where = array(
        	'config_key'       =>      $category
        );
        
        $update_array = array(
        	'config_val'       =>      serialize($config)
        );
        
    	return $this -> setUpdate($update_array) -> where($where) -> update();
    }
    
    /**
     * @tutorial Get the system web config information
     * 
     */
    public function get_configs($filter = '')
    {
    	$mysql = $this -> cleanUp() -> from ('system_config') -> select('*');
    	
    	$where = array('spec_shw'=>1);
    	
    	if(!empty($filter)){
    		/**
    		 * Get specific configuration
    		 */
    		$where = array(
    				'config_key'       =>      $filter
    		);
    		
    		return $mysql -> where($where) -> getOne();
    	}
    	else{
    		
    		return $mysql -> where($where) ->  query();
    	}
        
    }
    
    public function get_configs_fields($key)
    {
    	if(empty($key))	return false;
    	
    	$where = array(
    			'config_key'       =>      $key
    	);
    	
    	return $this -> cleanUp() -> select('*')-> from('config_fields') -> where($where)-> order('dis_ord','asc')-> query();
    	
    }
    
}

?>