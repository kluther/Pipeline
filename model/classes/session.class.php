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
		
		$_SESSION['user_id'] = $user->getId();
		$_SESSION['username'] = $user->getUserName();			
	}
	
	public static function signOut()
	{
		session_unset();
		session_destroy();	
		$_SESSION = array();
	}
	
	public static function getMessage()
	{
		if(isset($_SESSION['message']))
			return $_SESSION['message'];
		else
			return null;			
	}
	
	public static function setMessage($message)
	{
		$_SESSION['message'] = $message;
	}

	public static function clearMessage()
	{	
		@session_start();
		$_SESSION['message'] = null;
		unset($_SESSION['message']);		
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
		if(isset($_SESSION['user_id']))
			return $_SESSION['user_id'];
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
		if(isset($_SESSION['username']))
			return $_SESSION['username'];
		else
			return null;
	}	
}
