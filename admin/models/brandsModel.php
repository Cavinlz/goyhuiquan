<?php

/** 
 * @author Cavinlz
 * 
 */
class brandsModel extends \Model
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('brands');
    }
    
    
    public function get_list($page , $conditions = array())
    {
        $page = (isset($page) && $page > 0 )?$page:1;

        $query	= $this -> select('*');
    
        $conditions = array_merge($conditions,array('brand_shw_flag'=>1));
        
        if($conditions){
            $query = $query -> where($conditions);
        }
    
        return $query -> limit($page)-> order('id') -> query();
    }
    
    
    public function get_brands_list()
    {
        return $this -> select('*') -> where(array('brand_shw_flag'=>1)) -> query();
    }
    
    public function get_brand_info($where)
    {
        if(!is_array($where)){
            $where = array($this->pk => $where);
        }
        
        $query = $this -> select('*') -> where($where);
        
        return $this -> get_memcached_query(false, 'brandinfo', 'get_brand_info');
    }
    
    public function incr_brands_likes()
    {
        
    }
}

?>