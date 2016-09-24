<?php
/**
 *
 * @author Administrator
 *        
 */
class activitiesModel extends \Model
{

    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('activities');
    }
    
    
    public function get_list($page , $conditions = array())
    {
        $page = (isset($page) && $page > 0 )?$page:1;

        $query	= $this -> select('*');
    
        if($conditions){
            $query = $query -> where($conditions);
        }
    
        return $query -> limit($page)-> order('start_timestamp') -> query();
    }
    
    public function get_act_list()
    {
        return $this -> select('*') ->  query();
    }
}

?>