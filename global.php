<?php

@session_start();

if(false === function_exists('lcfirst'))
{
    /**
     * Make a string's first character lowercase
     *
     * @param string $str
     * @return string the resulting string.
     */
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}

function __autoload($class_name)
{
	require_once 'model/classes/' . lcfirst($class_name) . '.class.php';
}

// section IDs
define('ACTIVITY_ID',1);
define('BASICS_ID',2);
define('TASKS_ID',3);
define('DISCUSSIONS_ID',4);
define('PEOPLE_ID',5);

set_include_path(dirname(__FILE__));
require_once("config.php");
require_once("lib/Soup/Soup.php");
