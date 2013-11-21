<?php

class DocTypes extends DbObject
{
	protected $id;
	protected $typeName;
        protected $currentVersion;
        protected $description;

	const DB_TABLE = 'documenttype';
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'typeName' => '',
			'currentVersion' => 0,
			'description' => '',
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->typeName = $args['typeName'];
		$this->currentVersion = $args['currentVersion'];
		$this->description = $args['description'];	
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
			'typeName' => $this->typeName,
			'currentVersion' => $this->currentVersion,
			'description' => $this->description,
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
        public static function getAllTypes($limit=null) {
                $db = Db::instance();
            
                $query = "SELECT * FROM ".self::DB_TABLE;
                if (mysql_num_rows($db->lookup($query)) != 0) {
                    $query .= " ORDER BY id ASC";
                }
		if($limit != null)
			$query .= " LIMIT ".$limit;
	
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$types = array();
		while($row = mysql_fetch_assoc($result))
			$types[$row['id']] = $row;
		return $types;	
	}
        
        public static function getArrayById($id)
        {
                $query = "SELECT * FROM ".self::DB_TABLE." WHERE id = '".$id."'";
                $db = Db::instance();
		$result = $db->lookup($query);
                if(!mysql_num_rows($result)) return null;
                
                return mysql_fetch_array($result);
                
        }
        
	public function getID()
        {
                return $this->id;
        }
        
        public function setID($newID)
        {
                $this->id = $newID;
                $this->modified = true;
        }
        
        public function getTypeName()
        {
                return $this->typeName;
        }
        
        public function setTypeName($newTypeName)
        {
                $this->typeName = $newTypeName;
                $this->modified = true;
        }
        
        public function getCurrentVersion()
        {
                return $this->currentVersion;
        }
        
        public function setCurrentVersion($newCurrentVersion)
        {
                $this->currentVersion = $newCurrentVersion;
                $this->modified = true;
        }
        
        public function getDescription()
        {
                return $this->description;
        }
        
        public function setDescription($newDescription)
        {
                $this->description = $newDescription;
                $this->modified = true;
        }
        
}