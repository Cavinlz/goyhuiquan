<?php

/** 
 * @author Cavinlz
 * 
 */
class listviewView extends \TableView
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
               // CLanguage::Text('THEAD.card_act')		,
                CLanguage::Text('THEAD.card_name')		,
                CLanguage::Text('THEAD.card_brand')		,
                '<i class="icon-time"></i> '. CLanguage::Text('THEAD.card_expried')	,
                CLanguage::Text('THEAD.card_status')	,
                CLanguage::Text('THEAD.card_show_status')	,
                CLanguage::Text('THEAD.card_stock')		,
                CLanguage::Text('THEAD.OPERATION')	,
    
        );
    
    
    }
    
    
    public function config_table_body()
    {
        $results = array();
    
        $router = $this ->load('router');
    
        if($this -> data)
        {
            $this -> load_form();
            $counter = 1;
            foreach($this -> data as $val)
            {
                $opr = $this -> get_record_operations($val);
    
                $checkproperties = array('data-id'=>$val['id']);
                
                if ($val['shw_flag'] == CARD_SHOW_FLAG) {
                    $checkproperties['checked'] = 'checked';
                }
                
                $switch = new CSwitch('', 'mySwitch',array('data-on-label'=>'投放','data-off-label'=>'未投放','data-on'=>'warning'),$checkproperties);
                
                if($val['card_effect_term'] == DATE_FIX_RANGE){
                    $val['date_range'] = date('Y-m-d',$val['begin_timestamp']).' 至 '.date('Y-m-d',$val['end_timestamp']);
                }
                
                $results[] = array(
                        	
                       // $val['id']				,
                        '“ '.$val['act_name'].' ”'				,
                        $val['card_name']		,
                        ($val['brand_name'])		,
                        $val['date_range']	,
                        card_status($val['card_status'])	,
                        $switch -> render(true),
                        $val['quantity'].' 个'	,
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
     * @param unknown $val - (to be used as deletion / edition / ,etc)
     */
    public function get_record_operations($val)
    {
        $key = $val['id'];
        
        $router = $this -> load('router');
    
        $controller = $router -> route_url['controller'];
        
        //$view 	= '<a href="'.sprintf($router -> return_url('cardcodes','sale').'/%d/?step=1',$key).'"><button class="btn btn-xs btn-default" id="tf-'.$key.'" data-id="'.$key.'" title="'.CLanguage::Text('BUTTON.DELIVY').'"><i class="icon-shopping-cart"></i> </button></a> ';
        
        if($val['use_custom_code'] == 1){
            $view 	.= '<a href="'.sprintf($router -> return_url('cardcodes','import').'/%d/?step=1',$key).'"><button class="btn btn-xs btn-default" id="tf-'.$key.'" data-id="'.$key.'" title="'.CLanguage::Text('BUTTON.IMPORTCARD').'"><i class="icon-upload-alt"></i> </button></a> ';
        }
        
        $view 	.= $this-> record_viewable ? '<a href="'.sprintf($router -> return_url('card','view').'/%d/',$key).'"><button class="btn btn-xs btn-default" id="view-'.$key.'" data-id="'.$key.'" title="View Details"><i class="icon-eye-open"></i> </button></a>':'';
        $edit 	= $this-> record_editable ? '<a href="'.sprintf($router -> return_url('card','edit').'/%d/',$key).'"><button class="btn btn-xs btn-default" id="edit-'.$key.'" data-id="'.$key.'" title="Edit Record"><i class="icon-pencil"></i> </button></a>':'';
        $rm		= $this-> record_delable ? '<a href="#"><button class="btn btn-xs btn-default" id="del-'.$key.'" data-id="'.$key.'" data-target="'.$router -> return_url('wechatcards','delcard').'"><i class="icon-remove"></i> </button></a>':'';
    
        return $view.' '.$edit.' '.$rm;
    }
    
    public function config_general_info()
    {
        $router = $this -> load('router');
    
        $this -> data['page_header'] = CLanguage::Text('MENU.WECHAT_CARD_LIST');
    
        $this -> data['page_icon']	=	'credit-card';
        $this -> data['page_nav']	=	'';
    }
}

?>