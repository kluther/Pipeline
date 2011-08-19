<?php

class Db
{
	private static $_instance = NULL;
	private $_conn;
	
	// Get the database singleton
	public static function instance()
	{
		if (self::$_instance === NULL) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}	
	
	private function __construct()
	{
		$host     = DB_HOST;
		$database = DB_NAME;
		$username = DB_USERNAME;
		$password = DB_PASSWORD;

		$conn = mysql_connect($host, $username, $password)
			or die ('Error: Could not connect to MySql database');

		mysql_select_db($database);
	}
	
	public function fetch($id, $class_name, $db_table)
	{
		if ($id === null) {
			return null;
		}
		
		$obj = ObjectCache::get($class_name, $id);
		
		if ($obj !== null) {
			return $obj;
		}
		
		$query = sprintf("SELECT * FROM %s WHERE id = '%s';",
				$db_table,
				$id
			);
		//echo $query;
		$result = $this->lookup($query);

		if(!mysql_num_rows($result))
		{
			return null;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			$obj = new $class_name($row);
			ObjectCache::set($class_name, $id, $obj);
			return $obj;
		}	
	}	
	
	public function store(&$obj, $class_name, $db_table, $data)
	{	
		// find out if this item already exists so we know to use INSERT or UPDATE
		if($obj->getId() === null)
		{
			// ID would only be null for a new item, so let's use INSERT		
			$query = $this->buildInsertQuery($db_table, $data);
			//echo $query;
			$this->execute($query); // execute the query we've built
			$obj->setId($this->getLastInsertID()); //get back the ID for the new item
			ObjectCache::set($class_name, $obj->getId(), $obj); // update cache
		}
		else
		{
			// item ID exists, so let's use UPDATE
			// only hit the database if the instance has been modified
			if($obj->getModified())
			{
				$query = $this->buildUpdateQuery($db_table, $data, $obj->getId());
				//echo $query;
				$this->execute($query); // execute the query we've built
			}
		}
		//echo $query; // print the query
		$obj->setModified(false); // reset the flag	
	}	
	
	// Formats a string for use in SQL queries.
	// Use this on ANY string that comes from external sources (i.e. the user).
	public function quoteString($s)
	{
		return "'" . mysql_real_escape_string($s) . "'";
	}
	
	// Formats a date (i.e. UNIX timestamp) for use in SQL queries.
	public function quoteDate($d)
	{
		return date("'Y-m-d H:i:s'", $d);
	}

	//Query the database for information
	public function lookup($query)
	{
		// do lots of SQL injection prevention here
	
		$result = mysql_query($query);
		if(!$result)
			die('Invalid query: ' . $query);
		return ($result);			
	}

	//Execute operations like UPDATE or INSERT
	public function execute($query)
	{
	
		//Best have lost most injection prevention here
		
		$ex = mysql_query($query);
		if(!$ex)
			die ('Query failed:' . mysql_error());
	}
	
	//Build an INSERT query.  Mostly here to make things neater elsewhere.
	//$table  -> Name of the table to insert into
	//$fields -> List of attributes to populate
	//$values -> Values that will populate the new row
	//RETURN  -> A mysql insert query in the form of:
	//					 "INSERT INTO <table> (<fields>) VALUES (<values>)"
	//NOTE: This function DOES NOT actually EXECUTE the query, only gives a 
	//			string to be used elsewhere.
	public function buildInsertQuery($table = '', $data = array())
	{
		$fields = '';
		$values = '';
	
		foreach ($data as $field => $value)
		{
			if($value !== null) // skip unset fields
			{
				$fields .= $field . ", ";
				$values .= $this->quoteString($value) . ", ";
			}
		}
		
		 // cut off the last ', ' for each
		$fields = substr($fields, 0, -2);
		$values = substr($values, 0, -2);

		$query = sprintf("INSERT INTO %s (%s) VALUES (%s);",
				$table,
				$fields,
				$values
			);
		
		return ($query);
	}
	
	public function buildUpdateQuery($table = '', $data = array(), $id=0)
	{
		$all_null = true;
		$query = "UPDATE " . $table . " SET ";

		foreach ($data as $field => $value)
		{
			if($value === null)
				$query .= $field . " = NULL, ";
			else
			{
				$query .= $field . " = " . $this->quoteString($value) . ", ";
				$all_null = false;
			}
		}
		
		$query = substr($query, 0, -2); // cut off the last ', '
		$query .= " WHERE id = '" . $id . "';";
		
		// only return a real query if there's something to update
		if($all_null)
			return '';
		else
			return ($query);
	}
	
	//Get the ID of the last row inserted into the database.  Useful for getting
	//the id of a new object inserted using AUTO_INCREMENT in the db.
	//RETURN -> The ID of the last inserted row
	public function getLastInsertID()
	{
		$query = "SELECT LAST_INSERT_ID() AS id";
		$result = mysql_query($query);
		if(!$result)
			die('Invalid query.');
			
		$row = mysql_fetch_assoc($result);
		return ($row['id']);
	}
}