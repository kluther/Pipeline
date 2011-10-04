<?php

Class Filter {

	/* 
	 *	@param email.
	 *	Return sanatized & validated email or False if filter fails.
	 */
	static function email($email = null)
	{
		if ($email != null)
		{
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}
		return false;
	}
	
	/* 
	 *	@param formatted text containing html tags.
	 *	Return sanatized text or False if filter fails.
	 */
	static function formattedText($input = null)
	{
		if ($input != null)
		{
			//acceptable tags
			//$tags = '<strong><i><b><img><span><div><p><em><br><sup><sub><table><tr><td><th><strike><li><ul><ol><hr><tbody><small><span><center><h1><h2><h3><h4><h5><h6>';
			$tags = '<strong><i><b><em><a>';
			$input = strip_tags($input, $tags);
			return filter_var($input,FILTER_SANITIZE_SPECIAL_CHARS);
		}
		return false;
	}
	
	/* 
	 *	@param text.
	 *	Return sanatized text or False if filter fails.
	 */
	static function text($input = null)
	{
		if ($input != null)
		{
		//	$input = htmlspecialchars($input);
			return filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
		}
		return false;
	}
	
	/* 
	 *	@param text (alphanum).
	 *	Return sanatized text or False.
	 */
	static function alphanum($string = null)
	{
		if ($string != null)
		{
			return preg_replace("/[^a-zA-Z0-9\s]/","", $string);
		}
		return false;
	}
	
	/* 
	 *	@param input (numeric).
	 *	Return validated input or False if filter fails.
	 */
	static function numeric($number = null)
	{
		if ($number != null)
		{
			if (!is_numeric($number))
			{
				return false;
			}
			return $number;
		}
		return false;
	}
	
	/* 
	 *	@param input (ipaddress).
	 *	Return validated input or False if filter fails.
	 */
	static function ipaddress($ip = null)
	{
		if ($ip != null)
		{
			return filter_var($ip, FILTER_VALIDATE_IP);
		}
		return false;
	}

	/* 
	 *	@param input (web url).
	 *	Return sanatized & validate input or False if filter fails.
	 */
	static function url($url = null)
	{
		if ($url != null)
		{
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = filter_var($url, FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW);
			return filter_var($url, FILTER_VALIDATE_URL);
		}
		return false;
	}
	
}

?>