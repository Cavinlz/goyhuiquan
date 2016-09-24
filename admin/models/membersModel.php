<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class membersModel extends \Model {
	
	/**
	 */
	public function __construct() {
		$this -> setTable('members');
		parent::__construct ();
	}
	
	public function check_member_exits($data)
	{
		if(!$openid = $data['openid']) return;

		return $this -> check_record_exists('',array('openid'=>$openid));
	}
	
	public function get_member_info($uid)
	{
	    if(!$uid) return;
	    
	    return $this -> select('*') -> where(array('id'=>$uid))-> getOne();
	}
	
	public function get_member_info_via_openid($openid)
	{
	    if(!$openid) return;
	    
	    return $this -> select('*') -> where(array('openid'=>$openid))-> getOne();
	}
	/**
	 * 
	 * 
	 * @param unknown $data
	 * @param number $flag   1 	> 	BASE Info (ie . openid only)
	 * 						 2	>	Full User Info 
	 */
	public function new_member($data, $flag = 1)
	{
	  
		return $this -> cleanUp() -> insert($data);
		
	}
	
	public function update_member_via_openid($openid, $updArray)
	{
	    if(empty($openid)) return;
	    
	    return $this -> setUpdate($updArray) -> where(array('openid'=>$openid)) -> update();
	    
	}
	
	public function update_member_via_uid($uid, $updArray)
	{
	    if(empty($uid)) return;
	     
	    return $this -> setUpdate($updArray) -> where(array('id'=>$uid)) -> update();
	     
	}
	
	public function upd_pro()
	{
	    $rs = $this -> setTable('pro_city')->select('*')->query();
	    
	    addFunc('pinyin');
	    $py=new Pinyin();
	    //$py->str2py($name);
	    
	    foreach($rs as $val){
	        
	        $this -> cleanUp()->setUpdate(array('py_name' =>$py->str2py($val['area_name'])))->where(array('id'=>$val['id']))->update();
	        
	        
	    }
	}
	
    public function get_list($page , $conditions = array())
    {
        $page = (isset($page) && $page > 0 )?$page:1;

        $query	= $this -> select('*');
    
        //$conditions = array_merge($conditions,array('brand_shw_flag'=>1));
        
        if($conditions){
            $query = $query -> where($conditions);
        }
    
        return $query -> limit($page)-> order('id') -> query();
    }
	
	
}

?>