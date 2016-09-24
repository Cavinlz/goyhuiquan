<?php
/**
 *
 * @author Cavinlz
 *        
 */
class membersController extends \Controller
{

    /**
     */
    public function indexOp ()
    {
        $model = $this -> model();
        
        $filter = $this -> get_filter_condition();
        
        //$filters = array_merge($basic_filter, $filter);
        
        $data = $model -> get_list($this -> router ->get('p'), $filter);
        
        $this -> Output('table',$data);
    }
    
    
    protected function get_filter_condition()
    {
        $session = $this -> load('session');
    
        $filter = array();
    
        $trigger = $this->router-> post('search');
        $page = $this->router->get('p');
    
        if(isset($trigger))
        {
    
            unset($session -> data['condition']);
            unset($session -> data['search']);
    
    
    
            if($filter) $session -> data['condition'] = $filter;
             
            //print_r($filter);
    
        }
        elseif(!empty($page))
        {
            if($session->data['condition'])
                $filter = $session->data['condition'];
        }
        else
        {
            unset($session -> data['condition']);
            unset($session -> data['search']);
        }
    
        return $filter;
    }
}

?>