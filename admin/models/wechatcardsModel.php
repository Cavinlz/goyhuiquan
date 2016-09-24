<?php

/** 
 * @author Administrator
 * 
 */
class wechatcardsModel extends \Model
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('cards');
    }
    
    public function new_card($arr)
    {
        return $this -> insert($arr);
    }
    
    public function new_general_card_details($arr)
    {
        return $this -> setTable('general_card') -> insert($arr);
    }
    
    public function get_cards($page , $conditions = array())
    {
        $page = (isset($page) && $page > 0 )?$page:1;
        
        $join_b = array('a.id'=>"b.cid");
        $join_c = array('a.brand_id'=>"c.id");
        $join_d = array('a.activity_id'=>"d.id");
        //$query	= $this -> select('a.*,b.basic_info,c.brand_name , c.logo_name as imgurl')
        //-> l_join('general_card', 'b', $join_b)
        $query	= $this -> select('a.*,c.brand_name , c.logo_name as imgurl, c.likes, d.act_name')
                        -> l_join('brands', 'c', $join_c)
                        -> l_join('activities', 'd', $join_d);;
        
        if($conditions){
            $query = $query -> where($conditions);
        }
        
        return $query -> limit($page)-> order('id','DESC') -> query();
    }
    
    public function update_card($updArray, $condition)
    {
        /**
         * If not mutiple condition then should be treated as the primary key
         */
        if(is_array($condition)){
            $where = $condition;
        }else{
            $where = array($this -> pk => $condition);
        }
        
        return ($this ->cleanUp()->setUpdate($updArray)->where($where)->update());
    }
    
    /**
     * 获取卡券的一般信息
     * 
     * @param unknown $condition
     * @param string $cache
     * @param string $cache_prefix
     * @return unknown
     */
    public function get_card($condition, $cache = true , $cache_prefix = '')
    {
        if(is_array($condition)){
            $where = $condition;
        }else{
            $where = array($this -> pk => $condition);
        }
        
        $this -> cleanUp()-> select('*')-> where($where);
        
        return ($cache)? $this -> get_memcached_query(false, $cache_prefix):$this -> getOne();
    }
    
    /**
     * 获取普通优惠券卡券的详细信息， 包括所有创建卡券时传入的数据 ，注意区别于 wechatcardsModel::get_card() 函数
     * 
     * @param unknown $condition
     * @param string $cache
     * @param string $cache_prefix
     */
    public function get_general_card_details($condition, $cache = true , $cache_prefix = '')
    {
        if(is_array($condition)){
            $where = $condition;
        }else{
            $where = array('a.'.$this -> pk => $condition);
        }
        
        $join_b = array('a.id'=>"b.cid");
        $join_c = array('a.brand_id'=>"c.id");
        $query	= $this -> cleanUp() -> select('a.*, b.basic_info,c.brand_name , c.logo_name as imgurl')
        -> l_join('general_card', 'b', $join_b)
        -> l_join('brands', 'c', $join_c)
        -> where($where)
        ;
        
        return ($cache)? $this -> get_memcached_query(false, $cache_prefix, 'get_general_card_details'):$this -> getOne();
    }
    
}

?>