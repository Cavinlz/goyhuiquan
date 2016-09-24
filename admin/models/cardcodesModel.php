<?php
/**
 *
 * @author Cavnlz
 *        
 */
class cardcodesModel extends \Model
{

    /**
     */
    public function __construct ()
    {
        parent::__construct();
        $this -> setTable('card_codes');
    }
    
    
    public function get_certain_card_codes($cid, $page='a')
    {
       
        $query	= $this -> select('*');
        
        $query = $query -> where(array('cid'=>$cid));
        
        if($page != 'a') //all
        {
            $page = (is_numeric($page) && $page > 0 )?$page:1; 
            
            $query -> limit($page) -> order('id','DESC');
        }
        
        return $query -> query();
    }
    
    public function batch_insert_cardcodes($cardcodes, $extvals)
    {
        
        if(!is_array($cardcodes)) return false;
        
        if($extvals && is_array($extvals)){
            
            $extkeys =  array();
            $extravals = array();
            foreach ($extvals as $key => $val)
            {
                $extkeys[] =  '`'.$key.'`';
                $extravals[] = '"'.$val.'"';
            }
            
            $extkeys[] = '`code_no`';
            $valstr = implode(',', $extravals);
        }
        
        $pendingInsertStr = '';
        foreach($cardcodes as $k) //started from A column
        {
            $pendingInsertStr .= '('.$valstr.',"'.$k.'"),';
        }
        //$pendingInsertStr = substr($pendingInsertStr,0,-1).') ,';
        $pendingInsertStr = substr($pendingInsertStr,0,strlen($pendingInsertStr)-1);
        
        $sql = 'INSERT INTO `'.$this ->tb.'`('.implode(',', $extkeys).') VALUES '.$pendingInsertStr;
        
        //logg::debug($sql);
        
        return $this -> model -> executeBasicQuery($sql);
        
    }
    
    public function get_certain_code($where)
    {
        $query	= $this -> select('*');
        
        $query = $query -> where($where);
        
        return $query -> getOne();
    }
    
    public function update_code($updArray, $condition)
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
     * 获取卡券中可领取的券码数量
     * 
     * @param unknown $card_pk
     */
    public function get_available_codes_counts($card_pk)
    {
        
        if(empty($card_pk)) return;
        
        $rs = $this -> cleanUp() -> select('count(*) as total') -> where(array(
                'cid' => (int)$card_pk,
                'code_status#BG' => CARD_CODE_STATUS_WARM
        )) -> getOne();
        
        return $rs['total'];
        
    }
    
    public function get_who_got_codes($wcardid, $limit = 0)
    {
        if(empty($wcardid)) return;
        
        $join_b = array('a.openid'=>"b.openid");
        
        $query = $this  -> cleanUp() 
                        -> select('b.*') 
                        -> l_join('members', 'b', $join_b)
                        -> where(array(
                                    'card_id' => $wcardid,
                                    'code_status#BG' => CARD_CODE_STATUS_NORMAL
                            )) 
                        -> order('get_time','DESC');
        
        $sql = $this -> getSqlStatement();
        
        if($limit)
            $sql.= ' LIMIT '.$limit;
        
        return $this -> query($sql);
    }
    
    public function get_available_get_counts($card_pk)
    {
    
        if(empty($card_pk)) return;
    
        $rs = $this -> cleanUp() -> select('count(*) as total') -> where(array(
                'cid' => (int)$card_pk,
                'code_status' => CARD_CODE_STATUS_NORMAL
        )) -> getOne();
    
        return $rs['total'];
    
    }
}

?>