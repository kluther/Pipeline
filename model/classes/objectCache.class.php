<?php

class ObjectCache
{
	private static $_cache = array();
	
	public static function get($dataType, $key)
	{
		$key = $dataType . "\n" . serialize($key);
		return @self::$_cache[$key];
	}
	
	public static function set($dataType, $key, $val)
	{
		$key = $dataType . "\n" . serialize($key);
		@self::$_cache[$key] = $val;
	}
	
	public static function remove($dataType, $key)
	{
		$key = $dataType . "\n" . serialize($key);
		unset(self::$_cache[$key]);
	}
}

?>