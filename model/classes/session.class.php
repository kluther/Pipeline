<?php

class Session
{
	public static function isLoggedIn()
	{
		if(self::getUserID() == null)
			return false;
		else
			return true;
	}
	
	// accepts either userId or userName
	public static function signIn($userInfo)
	{
		// is $userInfo a string or an integer?
		if(intval($userInfo) != 0)
			$user = User::load(intval($userInfo));
		else
			$user = User::loadByUserName($userInfo);
		
		// last login
		$user->setSecondLastLogin($user->getLastLogin());
		$user->setLastLogin(date("Y-m-d H:i:s"));
		$user->save();
		
		// set up session
		$_SESSION[BASE_URI]['user_id'] = $user->getId();
		$_SESSION[BASE_URI]['username'] = $user->getUserName();	
		$_SESSION[BASE_URI]['admin'] = $user->getAdmin();
	}
	
	public static function signOut()
	{
		session_unset();
		session_destroy();	
		$_SESSION = array();
	}
	
	public static function getMessage()
	{
		if(isset($_SESSION[BASE_URI]['message']))
			return $_SESSION[BASE_URI]['message'];
		else
			return null;			
	}
	
	public static function setMessage($message)
	{
		$_SESSION[BASE_URI]['message'] = $message;
	}

	public static function clearMessage()
	{	
		@session_start();
		$_SESSION[BASE_URI]['message'] = null;
		unset($_SESSION[BASE_URI]['message']);		
	}

	// public static function getProjectID()
	// {
		// if(isset($_GET['p']))
			// return $_GET['p'];
		// else
			// return null;
	// }

	public static function getUserID()
	{
		if(isset($_SESSION[BASE_URI]['user_id']))
			return $_SESSION[BASE_URI]['user_id'];
		else
			return null;
	}
	
	public static function getUser()
	{
		if(self::getUserID() != null)
			return (User::load(self::getUserID()));
	}
	
	public static function getUserName()
	{
		if(isset($_SESSION[BASE_URI]['username']))
			return $_SESSION[BASE_URI]['username'];
		else
			return null;
	}
	
	public static function isAdmin() {
		if(isset($_SESSION[BASE_URI]['admin']))
			return $_SESSION[BASE_URI]['admin'];
		else
			return null;
	}
}
