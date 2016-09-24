<?php
/**
 *
 * @author Cavinlz
 *        
 */
class brandlistView extends \TableView
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
                CLanguage::Text('brand_logo')		,
                CLanguage::Text('brand_name')		,
                CLanguage::Text('brand_status')
        
        );
        
    }
    
    
    public function config_table_body()
    {
        $results = array();
    
        $router = $this ->load('router');
    
        if($this -> data)
        {
            $counter = 1;
            
            foreach($this -> data as $val)
            {
                $opr = $this -> get_record_operations($val['id']);
                
                $filename = $val['logo_name'];

                $results[] = array(
                        	
                        $val['id']				,
                        $val['brand_name']				,
                        '<img src="'.$this -> router -> return_url('brands','getimg',array('s'=>$val['id'],'n'=>base64_encode($filename))).'" width="120"></img>',
                        $val['auth_flag'] == BRAND_AUTHORISED_FLAG ? '已授权 (截止日期 '.$val['auth_expired_date'].')' : '未授权',
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
    
        $this -> data['page_header'] = CLanguage::Text('MENU.BRAND_LIST_MGT');
    
        $this -> data['page_icon']	=	'sitemap';
        $this -> data['page_nav']	=	'';
    }
}

?>