<?php

/** 
 * @author Administrator
 * 
 */
class listviewController extends \Controller
{

    /**
     * 卡券管理
     */
    public function cardsOp()
    {
        CLanguage::load_lang_file('wechatcards');
        
        $model = $this -> model('wechatcards');
        
        $filter = $this -> get_filter_condition();
        
        $filter['shw_flag'] =  CARD_SHOW_FLAG;
        $filter['shw_flag#OR'] =  CARD_NOSHOW_FLAG;
        
        //$filters = array_merge($basic_filter, $filter);
        
        $data = $model -> get_cards($this -> router ->get('p'), $filter);
        
        /* 当不存在同名的model时， 需要手动设置页面导航设置，否则无法显示 */
        $view = $this -> view();
        $view -> set_page_vars($model -> get_pages_vars());
        
        CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/bootstrap-switch.css');
        
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