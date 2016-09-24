<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class loginModel extends Model{
	public  $userid;
	public  $userroleid;

	public  $session = null;
	/**
	 */
	function __construct($userid='',$userroleid='') 
	{
		parent::__construct();
		if($userid != '') 	$this -> userid = $userid;
		if($userroleid != '') $this -> userroleid = $userroleid;
		
		$this -> session = Console::getInstance('session');
		$this -> setTable('admin', 'uid');
		
		
	}
	
	public function alwMailUsed($mail)
	{
		$where = array('`email`'=>$mail);
		$rs = $this->cleanUp() -> select('1')->where($where)->getOne();
		if($rs){
			return true;
		}else{
			return false;
		}
	}

	
	public function user_login($acc,$pswd)
	{
		$where = array('`email`'=>$acc,'`logpwsd`'=>$pswd);
		return $this ->select('`uid`,`username`')->where($where)->getOne();
	}

	public function getUserInfo($select,$uid='')
	{
		$id = ($uid=='')?$this->userid:$uid;
		$this->query_vars = $this ->select($select)->where(array($this->pk=>$id))->getOne();
		return $this->query_vars;
	}
}

