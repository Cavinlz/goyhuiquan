<?php

/** 
 * @author Cavinlz
 * 
 */
class loginController extends Controller {
	
	/**
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	
	public function indexOp($params=array())
	{
		$model = $this ->load('model');
	    
		$this ->Output();
	}
	
	public function signinOp()
	{
		
		$username = trim($this -> router -> post('logacc'));
		$userpaswd = trim($this -> router -> post('logpswd'));
		
		$user = $this -> model();
		
		$json = array('code'=>GENERAL_SUCCESS_RETURN_CODE);
		
		if(!$result = $user -> user_login($username, $userpaswd))
		{
			$json = array(
					'code' 		=>      GENERAL_ERROR_RETURN_CODE,
					'msg'		=> 		CLanguage::Text('PswdInvalid')
			);
			
		}
		else
		{
		    $user -> session -> data['CZCOOL_ADMUSER_UID'] = base64_encode($result['uid']);
		    $user -> session -> data['CZCOOL_ADMUSER_UNAME']= $result['username'];
		    $json['go'] = $this -> router -> return_url('dashboard');
		}
		
		HttpResponse::setJsonOutput($json);
	}
	
	
	public function logoutOp()
	{
		$_SESSION['CZCOOL_ADMUSER_UID'] = '';
		$_SESSION['CZCOOL_ADMUSER_UNAME'] = '';

		unset($_SESSION['CZCOOL_ADMUSER_UID']);
		unset($_SESSION['CZCOOL_ADMUSER_UNAME']);

		session_destroy();
		$router = Console::getInstance('router');
		$logout_url = $router->return_url('login');
		loc_url($logout_url);
	}
}

?>