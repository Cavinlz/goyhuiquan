<?php

/** 
 * @author Administrator
 * 
 */
class activitiesController extends \Controller
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
    
    public function createOp ()
    {
        $this -> view('activity',false);
        $this -> Output('new.form');
    }
    
    
    public function createactOp()
    {
        $model = $this->model();
        
        $rt = $this -> router;
        
        $insertArr = array(
        
                'act_name' =>  $rt -> post('act_name'),
                'description'  =>  $rt -> post('description'),
                'start_timestamp'   =>  $rt -> post('begin_timestamp'),
                'end_timestamp'   =>  $rt -> post('end_timestamp'),
                'datetime'  =>  datetime()
        
        );
        
        $code = GENERAL_SUCCESS_RETURN_CODE;
        
        if(!$model -> insert($insertArr)){
            
            $code = GENERAL_ERROR_RETURN_CODE;
            $this -> redirect_rsp_page(CLanguage::Text('GENMSG.REQ_FAILED'), $code);
        }
        
        $this -> redirect_rsp_page($msg, $code);
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