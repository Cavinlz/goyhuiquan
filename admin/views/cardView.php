<?php

/** 
 * @author Administrator
 * 
 */
class cardView extends \TableView
{

    /**
     */
    public function config_table_headers()
    {
        
        $this -> my_table_headers	= array(
                 
                CLanguage::Text('THEAD.codes')		,
                CLanguage::Text('THEAD.code_staus')		,
                CLanguage::Text('THEAD.get_time')         ,
                CLanguage::Text('THEAD.used_time')         ,
                CLanguage::Text('THEAD.owner')         ,
        
        );
        
    }
    
    
    public function config_table_body()
    {
        $results = array();
    
        $router = $this ->load('router');
        
        if($this -> data['card_codes'])
        {
            $counter = 1;
    
            foreach($this -> data['card_codes'] as $val)
            {
                //$opr = $this -> get_record_operations($val['id']);
    
                //$filename = $val['logo_name'];
    
                $results[] = array(
                         
                        $val['code_no']				,
                        card_code_status($val['code_status'])				,
                        $val['get_time'],
                        $val['used_time'],
                        $val['openid'],
                );
            }
    
            $this -> get_records($results);
        }
    
        parent::config_table_body();
    
    }
    
    public function config_general_info()
    {
        $router = $this -> load('router');
    
        
    
        $this -> data['page_header']	=	CLanguage::Text('MENU.WECHAT_CARD_INFO');
        $this -> data['page_icon']		=	'credit-card';
        $this -> data['page_nav']		=	$this -> page_nav;
        $this -> data['jsmodel']       =   'brand';
    
    }
}

?>