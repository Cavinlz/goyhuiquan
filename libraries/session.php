<?php
if (defined("CZCool") or die( "Access Denied!" ));

class Session {
	public $data = array();

	public function __construct() {
		if (!session_id()) {
			/**
			 * whether to use cookie to store session ID..
			 */
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			/**
			 * Marks the cookie as accessible only through the HTTP protocol. 
			 * This means that the cookie won't be accessible by scripting languages, such as JavaScript.
			 * 
			 */
			ini_set('session.cookie_httponly', 'On');

			session_set_cookie_params(0, '/');
			session_start();
			@session_cache_limiter('private, must-revalidate');
		}

		$this->data =& $_SESSION;
	}

	public function get_id() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}
	
	public function set_filter($prop, $val = '')
	{
	    /**
	     * This if for global filtering conditions 
	     */
	    if($val=='')
	    {
	        return $this -> data['YZ_FILTERING'][$prop];
	    }
	    else{
	        $this -> data['YZ_FILTERING'][$prop] = $val;
	    }
	}
	
	public function rm_filter($prop)
	{
	    unset($this -> data['YZ_FILTERING'][$prop]);
	}
	
	/**
	 * Check Admin User login 
	 * 
	 */
	public function chkAdmUserLogin()
	{
	    if($this -> data['CZCOOL_ADMUSER_UID']==''){
	        return false;
	    }
	    return true;
	}
	
}