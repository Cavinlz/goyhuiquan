<?php

/** 
 * @author Cavinlz
 * 
 */
class User {
	
	const USER_SESSION_UID_KEY = 'YHG_USER_KEY';
	
	/**
	 */
	public static function register_user_session($data)
	{
		$session = Console::getInstance('session');
		
		$session -> data['WC_USER_OPENID']	= $data['openid'];
		$session -> data['WC_USER_AUTH']	= $data['user_auth'];
		$session -> data[self::USER_SESSION_UID_KEY] =	$data['id'];
		
	}
	
	public static function get_user_session_Ukey()
	{
		$session = Console::getInstance('session');
		
		return $session -> data[self::USER_SESSION_UID_KEY];
	}
	
	public static function get_user_session_Openid()
	{
	    $session = Console::getInstance('session');
	
	    return $session -> data['WC_USER_OPENID'];
	}
	
	public static function if_user_full_auth()
	{
	    $session = Console::getInstance('session');
	    
	    return $session -> data['WC_USER_AUTH'];
	}
}

?>