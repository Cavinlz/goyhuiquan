<?php

/**
 *
 * @author Cavinlz
 *        
 */
class membersView extends \TableView
{

    protected $record_editable = false;
    protected $record_viewable = true;
    protected $record_delable = false;
    
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
                CLanguage::Text('THER.IMG')		,
                CLanguage::Text('THER.NAME')		,
                CLanguage::Text('THER.REGION')		,
                '<i class="fa fa-mobile"></i> '. CLanguage::Text('THER.MOBILE')      ,
                CLanguage::Text('THER.LASTLOGIN')      ,
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
    
                $filename = $val['imgurl'];
                $imgdiv = '<div class="yhg-member"><img src="'.check_userimg($filename).'"></div>';
                
                $sex = ($val['sex'] == 1)?'<font><i class="fa fa-male"></i></font> ':'<font color=orange><i class="fa fa-female"></i></font> ';
                
                $results[] = array(
                         
                        $val['id']				,
                        $imgdiv				,
                        $sex. $val['nickname'],
                        $val['prov_name'].' '.$val['city_name'],
                        $val['mobile'],
                        $val['lasttime'],
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
    
        $this -> data['page_header'] = CLanguage::Text('MENU.WECHAT_USER_MGT');
    
        $this -> data['page_icon']	=	'group';
        $this -> data['page_nav']	=	'';
    }
}

?>