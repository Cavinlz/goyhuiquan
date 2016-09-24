<?php
/**
 *
 * @author Administrator
 *        
 */
class activitiesView extends \TableView
{

    protected $record_editable = true;
    protected $record_viewable = true;
    protected $record_delable = true;
    
    /**
     * 根据页面内容获取表头
     * 
     * (non-PHPdoc)
     * @see TableView::config_table_headers()
     */
    public function config_table_headers()
    {
        
        $this -> my_table_headers	= array(
                 
                '#'									,
                CLanguage::Text('act_name')		,
                CLanguage::Text('act_details')		,
                CLanguage::Text('act_startdate')    ,
                CLanguage::Text('act_enddate')    ,
        
        );
        
    }
    
    
    public function config_table_body()
    {
        $results = array();
    
        $router = $this ->load('router');
    
        if($this -> data)
        {
            $counter = 1;
            
            $timestr = '<i class="icon-time"></i> %s';
            
            foreach($this -> data as $val)
            {
                $opr = $this -> get_record_operations($val['id']);
                
                
                
                $results[] = array(
                        	
                        $val['id']				,
                        $val['act_name']				,
                        $val['description']        ,
                        sprintf($timestr,$val['start_timestamp'])        ,
                        sprintf($timestr,$val['end_timestamp'])         ,
                        $opr
                );
            }
    
            $this -> get_records($results);
        }
    
        parent::config_table_body();
    
    }
    
    /**
     * 获取操作按钮
     * 
     * @param unknown $key - Primary Key (to be used as deletion / edition / ,etc)
     */
    public function get_record_operations($key)
    {
        $router = $this -> load('router');
    
        $controller = $router -> route_url['controller'];
    
        //$view 	= $this-> record_viewable ? '<a href="'.sprintf($router -> return_url($this->edit_handler,'view').'?k=%d',$key).'"><button class="btn btn-xs btn-default" id="view-'.$key.'" data-id="'.$key.'" title="View Details"><i class="icon-eye-open"></i> </button></a>':'';
        $edit 	= $this-> record_editable ? '<a href="'.sprintf($router -> return_url($this->edit_handler,'translate').'?k=%d',$key).'"><button class="btn btn-xs btn-default" id="edit-'.$key.'" data-id="'.$key.'" title="Edit Record"><i class="icon-pencil"></i> </button></a>':'';
        $rm		= $this-> record_delable ? '<a href="'.sprintf($router -> return_url($controller,'del').'?k=%d',$key).'"><button class="btn btn-xs btn-default" id="del-'.$key.'" data-id="'.$key.'" title="Remove Record"><i class="icon-remove"></i> </button></a>':'';
    
        return $view.' '.$edit.' '.$rm;
    }
    
    
    public function config_general_info()
    {
        $router = $this -> load('router');
    
        $this -> data['page_header'] = CLanguage::Text('MENU.ACT_MGT');
    
        $this -> data['page_icon']	=	'gift';
        $this -> data['page_nav']	=	'';
    }
}

?>