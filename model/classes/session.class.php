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
	public static function signIn($userInfo, $remember=false)
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
		
		// set up cookies	
		$expireTime = time()+30*24*60*60; // 30 days
		$expire = ($remember) ? $expireTime  : false;
		setcookie('user_id', $user->getID(), $expire, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('username', $user->getUserName(), $expire, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('admin', $user->getAdmin(), $expire, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('instructor', $user->getInstructor(), $expire, COOKIE_PATH, COOKIE_DOMAIN);
	}
	
	public static function signOut()
	{
		// clear the cookies
		setcookie('user_id', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('username', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('admin', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
		setcookie('instructor', '', time()-3600, COOKIE_PATH, COOKIE_DOMAIN);
	}
	
	public static function getUserID()
	{
		if(isset($_COOKIE['user_id']))
			return $_COOKIE['user_id'];
		else
			return null;
	}
	
	public static function getUser()
	{
		return (User::load(self::getUserID()));
	}
	
	public static function getUserName()
	{
		if(isset($_COOKIE['username']))
			return $_COOKIE['username'];
		else
			return null;
	}
	
	public static function isAdmin() {
		if(isset($_COOKIE['admin']))
			return $_COOKIE['admin'];
		else
			return null;
	}

	public static function isInstructor()
	{
		if(isset($_COOKIE['instructor']))
			return $_COOKIE['instructor'];
		else
			return null;
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
		$_SESSION[BASE_URI]['message'] = null;
		unset($_SESSION[BASE_URI]['message']);		
	}
}
