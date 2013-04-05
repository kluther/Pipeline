<?php

class Interest extends DbObject
{
	protected $id;
	protected $userID;
	protected $title;
	protected $slug;

	const DB_TABLE = 'interest';
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'user_id' => 0,
			'title' => '',
			'slug' => '',
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['user_id'];
		$this->title = $args['title'];
		$this->slug = $args['slug'];
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
			'user_id' => $this->userID,
			'title' => $this->title,
			'slug' => $this->slug
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
}