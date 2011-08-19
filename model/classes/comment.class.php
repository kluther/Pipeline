<?php

class Comment extends DbObject
{
	protected $id;
	protected $creatorID;
	protected $projectID;
	protected $taskID;
	protected $updateID;
	protected $parentID;
	protected $message;
	protected $deleted;
	protected $dateCreated;
		
	const  DB_TABLE = 'comment';
	
	public function __construct($args = array())
	{
		$defaultArgs = array
		(
			'id' => null,
			'creator_id' => 0,
			'project_id' => 0,
			'task_id' => null,
			'update_id' => null,
			'parent_id' => null,
			'message' => '',
			'date_created' => null,
			'deleted' => 0
		);
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['creator_id'];
		$this->projectID = $args['project_id'];
		$this->taskID = $args['task_id'];
		$this->updateID = $args['update_id'];
		$this->parentID = $args['parent_id'];
		$this->message = $args['message'];
		$this->dateCreated = $args['date_created'];
		$this->deleted = $args['deleted'];
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
		// map database fields to class properties; omit id and dateTimeCreated
		$db_properties = array(
			'creator_id' => $this->creatorID,
			'project_id' => $this->projectID,
			'task_id' => $this->taskID,
			'update_id' => $this->updateID,
			'parent_id' => $this->parentID,
			'message' => $this->message,
			'deleted' => $this->deleted
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	public function delete()
	{
		$db = Db::instance();
		$query = "UPDATE ".self::DB_TABLE." SET deleted=1 WHERE id=".$this->id;
		$result = $db->lookup($query);
		return $result;
	}
	
	// public function restore()
	// {
		// $db = Db::instance();
		// $query = "UPDATE ".self::DB_TABLE." SET deleted=0 WHERE id=".$this->id;
		// $result = $db->lookup($query);
		// return $result;	
	// }
	
	public static function deleteByID($id=null)
	{
		if ($id == null) {return null;}
		
		$comment = self::load($id);
		$result = $comment->delete();
		return $result;
	}
	
	// public function isDeleted()
	// {
		// // if the comment itself is deleted, we can stop here
		// if($this->deleted)
			// return true;
		// elseif($this->nodeID != null)
		// {
			// // it's submission comment
			// $version = Version::load($this->nodeID);
			// // if parent version is deleted, so is the comment
			// if($version->isDeleted())
				// return true;
			// else
				// return false;
		// }
		// else
		// {
			// // it's a project comment
			// return false;
		// }
	// }
	
	public function getReplies() {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE parent_id != id";
		$query .= " AND parent_id = ".$this->id;
		$query .= " ORDER BY date_created ASC";
	
		$db = Db::instance();
		$result = $db->lookup($query);		
		if (!mysql_num_rows($result)) {return null;}
		
		$comments = array();
		while ($row = mysql_fetch_assoc($result))
			$comments[$row['id']] = self::load($row['id']);
		
		return $comments;		
	}
	
	// ---Static Methods--- //
	
	public static function getByTaskID($taskID=null, $limit=null, $deleted=false)
	{
		if ($taskID == null) return null;
		
		$query = "SELECT DISTINCT parent_id FROM ".self::DB_TABLE;
		$query .= " WHERE task_id=".$taskID;
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		$query .= " ORDER BY date_created DESC";
		if($limit!=null)
			$query .= " LIMIT ".$limit;		
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);		
		if (!mysql_num_rows($result)) {return null;}
		
		$comments = array();
		while ($row = mysql_fetch_assoc($result))
			$comments[$row['parent_id']] = self::load($row['parent_id']);
		
		return $comments;		
	}
	
	public static function getByUpdateID($updateID=null, $limit=null, $deleted=false)
	{
		if ($updateID == null) return null;
		
		$query = "SELECT DISTINCT parent_id FROM ".self::DB_TABLE;
		$query .= " WHERE update_id=".$updateID;
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		$query .= " ORDER BY date_created DESC";
		if($limit!=null)
			$query .= " LIMIT ".$limit;		
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);		
		if (!mysql_num_rows($result)) {return null;}
		
		$comments = array();
		while ($row = mysql_fetch_assoc($result))
			$comments[$row['parent_id']] = self::load($row['parent_id']);
		
		return $comments;		
	}
	
	// --- only getters and setters below here --- //	
	
	public function getID()
	{
		return ($this->id);
	}
	
	public function setID($newID)
	{
		$this->id = $newID;
		$this->modified = true;
	}
	
	public function getCreatorID()
	{
		return ($this->creatorID);
	}
	
	public function setCreatorID($newCreatorID)
	{
		$this->creatorID = $newCreatorID;
		$this->modified = true;
	}
	
	public function getProjectID()
	{
		return ($this->projectID);
	}
	
	public function setProjectID($newProjectID)
	{
		$this->projectID = $newProjectID;
		$this->modified = true;
	}
	
	public function getTaskID() {
		return ($this->taskID);
	}
	
	public function setTaskID($newTaskID) {
		$this->taskID = $newTaskID;
		$this->modified = true;
	}
	
	public function getUpdateID() {
		return ($this->updateID);
	}
	
	public function setUpdateID($newUpdateID) {
		$this->updateID = $newUpdateID;
		$this->modified = true;
	}
	
	public function getParentID()
	{
		return ($this->parentID);
	}
	
	public function getParent()
	{
		return (this::load($this->parentID));
	}
	
	public function setParentID($newParentID)
	{
		$this->parentID = $newParentID;
		$this->modified = true;
	}
	
	public function getMessage()
	{
		return ($this->message);
	}
	
	public function setMessage($newMessage)
	{
		$this->message = $newMessage;
		$this->modified = true;
	}
	
	public function getDateCreated()
	{
		return ($this->dateCreated);
		$this->modified = true;
	}
	
	public function setDateCreated($newDateCreated)
	{
		$this->dateCreated = $newDateCreated;
		$this->modified = true;
	}
	
	public function getDeleted()
	{
		return ($this->deleted);
	}
	
	public function setDeleted($newDeleted=0)
	{
		$this->deleted = $newDeleted;
		$this->modified = true;
	}	
}
