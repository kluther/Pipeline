<?php

class Accepted extends DbObject
{
	protected $id;
	protected $creatorID;
	protected $projectID;
	protected $taskID;
	protected $status;
	protected $dateCreated;

	const DB_TABLE = 'accepted';
	
	const STATUS_RELEASED = 0;
	const STATUS_ACCEPTED = 2;
	const STATUS_FEEDBACK = 3;
	const STATUS_PROGRESS = 4;
	const STATUS_COMPLETED = 1;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'creator_id' => 0,
			'project_id' => 0,
			'task_id' => 0,
			'status' => 0,
			'date_created' => null
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['creator_id'];
		$this->projectID = $args['project_id'];		
		$this->taskID = $args['task_id'];
		$this->status = $args['status'];
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
			'creator_id' => $this->creatorID,
			'project_id' => $this->projectID,
			'task_id' => $this->taskID,	
			'status' => $this->status,
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}	

	public function getUpdates() {
		return (Update::getByAcceptedID($this->id));
	}
	
	public function getLatestUpdate() {
		$updates = $this->getUpdates();
		if($updates != null) {
			return (reset($updates));
		} else {
			return null;
		}
	}
	
	// get all accepts for this taskID ... used on Task page
	public static function getAcceptedBy($taskID=null)
	{
		if($taskID == null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE task_id = ".$taskID;
		$query .= " AND status != ".self::STATUS_RELEASED;
		$query .= " ORDER BY date_created DESC, status DESC";
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$accepted = array();
		while($row = mysql_fetch_assoc($result))
			$accepted[$row['id']] = self::load($row['id']);
		return $accepted;		
	}
	
	
	// has user accepted any task in this project?
	public static function hasAccepted($userID=null, $projectID=null) {
		if( ($userID == null) || ($projectID == null) ) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE creator_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		$query .= " AND status != ".self::STATUS_RELEASED;

		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;

		$accepted = null;
		if($row = mysql_fetch_assoc($result))
			$accepted = self::load($row['id']);
			
		if($accepted != null)
			return true;
		else
			return false;
	}
	
	// has user accepted THIS task?
	public static function hasAcceptedTask($userID=null, $taskID=null) {
		if( ($userID == null) || ($taskID == null) ) return null;
		$accepted = self::getByUserID($userID, $taskID);
		if($accepted != null)
			return true;
		else
			return false;
	}
	
	public static function getByUserID($userID=null, $taskID=null)
	{
		if($userID == null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE creator_id = ".$userID;
		$query .= " AND status != ".self::STATUS_RELEASED;
		if($taskID != null)
			$query .= " AND task_id = ".$taskID;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;

		$accepted = null;
		if($row = mysql_fetch_assoc($result))
			$accepted = self::load($row['id']);
		return $accepted;			
	}
	
	public static function getByTaskID($taskID=null, $limit=null)
	{
		if($taskID == null) return null;
		
		$query = "SELECT a.id AS id FROM ".self::DB_TABLE." a";
		$query .= " INNER JOIN ".User::DB_TABLE." u ON ";
		$query .= " a.creator_id = u.id";
		$query .= " WHERE a.task_id = ".$taskID;
		$query .= " AND a.status != ".self::STATUS_RELEASED;
		$query .= " ORDER BY u.username ASC";
		//$query .= " ORDER BY status DESC, date_created DESC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$accepted = array();
		while($row = mysql_fetch_assoc($result))
			$accepted[$row['id']] = self::load($row['id']);
		return $accepted;			
	}
	
	public function getNumAccepted()
	{
		$accepted = Accepted::getByTaskID($this->getID());
		return (count($accepted));
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
	
	public function getTaskID()
	{
		return ($this->taskID);
	}
	
	public function setTaskID($newTaskID)
	{
		$this->taskID = $newTaskID;
		$this->modified = true;
	}
	
	public function getStatus()
	{
		return ($this->status);
	}
	
	public static function getStatusName($status)
	{
		if($status == self::STATUS_ACCEPTED)
			return "started";
		elseif($status == self::STATUS_FEEDBACK)
			return "seeking feedback";
		elseif($status == self::STATUS_COMPLETED)
			return "finished";
		elseif($status == self::STATUS_PROGRESS)
			return "working";
		else
			return "stopped";		
	}
	
	public function setStatus($newStatus)
	{
		$this->status = $newStatus;
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
}