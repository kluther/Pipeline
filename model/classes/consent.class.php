<?php

class Consent extends DbObject
{
	protected $id;
	protected $email;
	protected $name;
	
	const DB_TABLE = 'consent';

	public function __construct($args = array())
	{
		$defaultArgs = array(
			'id' => null,
			'email' => '',
			'name' => null,
			'date_created' => null
		);
		
		$args += $defaultArgs; // combine the arrays
		
		$this->id = $args['id'];
		$this->email = $args['email'];
		$this->name = $args['name'];
		$this->dateCreated = $args['date_created'];
	}
	
	public static function load($id)
	{
		$db = Db::instance();
		$obj = $db->fetch($id, __CLASS__, self::DB_TABLE);
		return $obj;
	}

	public function save()
	{
		$db = Db::instance();
		// map database fields to class properties; omit id and dateCreated
		$db_properties = array(
			'email' => $this->email,
			'name' => $this->name
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}

	// --- only getters and setters below here --- //
	
	public function getID()
	{
		return ($this->id);
	}
	
	public function setID($newID)
	{
		$this->modified = true;
		$this->id = $newID;
	}
	
	public function getEmail()
	{
		return ($this->email);
	}
	
	public function setEmail($newEmail)
	{
		$this->modified = true;	
		$this->email = $newEmail;
	}
	
	public function getName()
	{
		return ($this->name);
	}
	
	public function setName($newName)
	{
		$this->modified = true;
		$this->name = $newName;
	}

	public function getDateCreated()
	{
		return ($this->dateCreated);
	}
	
	public function setDateCreated($newDateCreated)
	{
		$this->modified = true;	
		$this->dateCreated = $newDateCreated;
	}

}