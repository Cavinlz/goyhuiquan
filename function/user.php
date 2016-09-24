<?php
if ( defined( "CZCool" )  or die( "Access Denied!" ));
/** 
 * @author Cavinlz
 * 
 */
class User extends Model{
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
		$this -> setTable('member_account', 'uid');
		
		
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

	public function chkUserLogin()
	{
		if($this -> session -> data['TRANS_USER_UID']=='' || $this -> session -> data['TRANS_USER_RID'] == ''){
			return false;
		}
		
		$userid = base64_decode($this -> session -> data['TRANS_USER_UID']);
		
		if(!$rs = $this -> check_record_exists($userid)){
			return false;
		}
		
		/**
		 * Check whether the account has been locked
		 * 
		 */
		if(!$rs['acc_status']){
			return false;
		}
		
		return true;
	}
	
	public function user_login($acc,$pswd)
	{
		$where = array('`email`'=>$acc,'`logpwsd`'=>$pswd);
		return $this ->select('`uid`,`role_id`,`username`,`acc_status`')->where($where)->getOne();
	}
	
	public static function isTranslationCompany()
	{
		if(self::GSRID() == ROLE_TRANSLATIONCPY) return true;
	}

	public static function isTranslator()
	{
		if(self::GSRID() == ROLE_TRANSLATOR) return true;
	}
	
	public static function isCompanyClient()
	{
		if(self::GSRID() == ROLE_CPYCLIENT) return true;
	}
	
	public static function isAccountant()
	{
		if(self::GSRID() == ROLE_ACCOUNTANT) return true;
	}
	
	public static function isTranslationEmpee()
	{
		if(self::GSRID() == ROLE_TRANSLATIONCPY_EMPEE) return true;
	}
	
	public static function isSystemAdmin()
	{
		if(self::GSRID() == ROLE_ITADMIN || self::GSRID() == ROLE_SYSADMIN) return true;
	}

	public static function isTechnicalSupport()
	{
		if(self::GSRID() == ROLE_ITADMIN) return true;
	}
	
	public static function getUserTB()
	{
		return DBPRE.'member_account';
	}
	
	public function getUserInfo($select,$uid='')
	{
		$id = ($uid=='')?$this->userid:$uid;
		$this->query_vars = $this ->select($select)->where(array($this->pk=>$id))->getOne();
		return $this->query_vars;
	}
	
	/**
	 * Get Session User ID
	 *
	 * @return string
	 */
	public static function GSUID()
	{
		return base64_decode($_SESSION['TRANS_USER_UID']);
	}
	/**
	 * Get Session User Role ID
	 *
	 * @return string
	 */
	public static function GSRID()
	{
		return base64_decode($_SESSION['TRANS_USER_RID']);
	}
	
	/**
	 * 
	 */
	public function get_role($userid='')
	{
		$uid =  ($userid=='')?$this->userid:$userid;
		
		$rs= $this ->select('role_id')->where(array($this->pk=>$uid))->getOne();
		
		$this -> userroleid = $rs['role_id'];
		
		return $this -> userroleid;
	}
	
	public function set_userid($userid, $get_user_info = true)
	{
		$this ->userid = $userid;
		
		if($get_user_info == true){
			$this ->getUserInfo('*');
		}
		
		return $this;
	}
}

