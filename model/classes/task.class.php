<?php

class Task extends DbObject
{
	protected $id;
	protected $creatorID;
	protected $leaderID;
	protected $projectID;
	protected $title;
	protected $description;
	protected $deadline;
	protected $status;
	protected $numNeeded;
	protected $dateCreated;

	const DB_TABLE = 'task';
	
	const STATUS_OPEN = 1;
	const STATUS_CLOSED = 0;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'creator_id' => 0,
			'leader_id' => null,
			'project_id' => 0,
			'title' => '',
			'description' => '',
			'deadline' => null,
			'status' => 0,
			'num_needed' => null,
			'date_created' => null
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['creator_id'];
		$this->leaderID = $args['leader_id'];
		$this->projectID = $args['project_id'];
		$this->title = $args['title'];
		$this->description = $args['description'];
		$this->deadline = $args['deadline'];		
		$this->status = $args['status'];
		$this->numNeeded = $args['num_needed'];
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
			'leader_id' => $this->leaderID,
			'project_id' => $this->projectID,
			'title' => $this->title,
			'description' => $this->description,
			'deadline' => $this->deadline,		
			'status' => $this->status,
			'num_needed' => $this->numNeeded
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	public static function getYourTasks($userID=null, $projectID=null, $limit=null) {
		return (self::getByUserID($userID, $projectID, null, $limit));
	}
	
	public static function getByUserID($userID=null, $projectID=null, $private=null, $limit=null)
	{
		if($userID == null) return null;
		
		$query = "SELECT t.id AS id FROM ".self::DB_TABLE." t";
		$query .= " INNER JOIN ".Project::DB_TABLE." p";
		$query .= " ON t.project_id = p.id";
		$query .= " WHERE ((t.leader_id = ".$userID.")";
		$query .= " OR (t.id IN ";
			$query .= " (SELECT task_id FROM ".Accepted::DB_TABLE;
			$query .= " WHERE creator_id = ".$userID;
			$query .= " AND status != ".Accepted::STATUS_RELEASED.")";
		$query .= " ))";
		if($projectID != null)
			$query .= " AND t.project_id = ".$projectID;
		if($private === true) {
			$query .= " AND private=1";
		} elseif($private === false) {
			$query .= " AND private=0";
		}
		$query .= " ORDER BY t.status DESC, ISNULL(t.deadline) ASC, t.title ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		//echo $query;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$tasks = array();
		while($row = mysql_fetch_assoc($result))
			$tasks[$row['id']] = self::load($row['id']);
		return $tasks;			
	}	
	
        //This function will return the tasks in a project that do not apply to a specified user. You can pass in a value of 'true"
        // for 'excludeClosedTasks' which will prevent closed tasks from being returned in the result
	public static function getMoreTasks($userID=null, $projectID=null, $limit=null, $excludeClosedTasks=false)
	{
		if( ($userID == null) || ($projectID == null) ) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE (project_id = ".$projectID;
		$query .= " AND leader_id != ".$userID.")";
		$query .= " AND (id NOT IN ";
			$query .= " (SELECT task_id FROM ".Accepted::DB_TABLE;
			$query .= " WHERE project_id = ".$projectID;
			$query .= " AND creator_id = ".$userID;
			$query .= " AND status != ".Accepted::STATUS_RELEASED.")";
		$query .= ")";
                if ($excludeClosedTasks)
                    $query .= " AND status != " .self::STATUS_CLOSED;
		$query .= " ORDER BY status DESC, ISNULL(deadline) ASC, title ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		//echo $query;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$tasks = array();
		while($row = mysql_fetch_assoc($result))
			$tasks[$row['id']] = self::load($row['id']);
		return $tasks;			
	}
        
        //This function will return tasks that do not have someone assigned to them yet. You can pass in a value of 'true"
        // for 'excludeClosedTasks' which will prevent closed tasks from being returned in the result
        public static function getUnclaimedTasks($userID=null, $projectID=null, $limit=null, $excludeClosedTasks=false)
        {
            if( ($userID == null) || ($projectID == null) ) return null;
            
            $unclaimedTasks = array();
            $moreTasks = Task::getMoreTasks($userID,$projectID,$limit,$excludeClosedTasks);
            foreach ($moreTasks as $task) {
                $numAccepted = $task->getNumAccepted();
                
                if ($numAccepted == 0) {
                    array_push($unclaimedTasks,$task);
                }
            }
            return $unclaimedTasks;
        }
	
	public static function getByLeaderID($projectID=null, $leaderID=null, $limit=null)
	{
		if( ($projectID == null) || ($leaderID == null) ) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id = ".$projectID;
		$query .= " AND leader_id = ".$leaderID;
		if($limit != null)
			$query .= " LIMIT ".$limit;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$tasks = array();
		while($row = mysql_fetch_assoc($result))
			$tasks[$row['id']] = self::load($row['id']);
		return $tasks;			
	}
	
	public static function getByProjectID($projectID=null, $status=null, $limit=null)
	{
		if($projectID == null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id = ".$projectID;
		if($status != null) {
			$query .= " AND status = ".$status;
		}
		$query .= " ORDER BY status DESC, ISNULL(deadline) ASC, title ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$tasks = array();
		while($row = mysql_fetch_assoc($result))
			$tasks[$row['id']] = self::load($row['id']);
		return $tasks;			
	}
        
        //This function will return all of the closed tasks for a project
        public static function getClosedTasks($projectID=null, $limit=null)
	{
		if ($projectID == null)  return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id = ".$projectID;
		$query .= " AND status = ".self::STATUS_CLOSED;
		if($limit != null)
			$query .= " LIMIT ".$limit;
			
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$tasks = array();
		while($row = mysql_fetch_assoc($result))
			$tasks[$row['id']] = self::load($row['id']);
		return $tasks;			
	}
	
	public function getNumAccepted()
	{
		$accepted = Accepted::getByTaskID($this->getID());
		return (count($accepted));
	}
	
	// used on Task page
	
	public function getAcceptedBy()
	{
		$acceptedBy = Accepted::getAcceptedBy($this->getID());
		return $acceptedBy;
	}
	
	public function getUpdates() {
		return (Update::getByTaskID($this->id));
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
	
	public function getLeaderID()
	{
		return ($this->leaderID);
	}
	
	public function setLeaderID($newLeaderID)
	{
		$this->leaderID = $newLeaderID;
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
	
	public function getTitle()
	{
		return ($this->title);
	}
	
	public function setTitle($newTitle)
	{
		$this->title = $newTitle;
		$this->modified = true;
	}	
	
	public function getDescription()
	{
		return ($this->description);
	}
	
	public function setDescription($newDescription)
	{
		$this->description = $newDescription;
		$this->modified = true;
	}
	
	public function getStatus()
	{
		return ($this->status);
	}
	
	public static function getStatusName($status)
	{
		if($status == self::STATUS_OPEN)
			return "open";
		else
			return "closed";
	}
	
	public function setStatus($newStatus)
	{
		$this->status = $newStatus;
		$this->modified = true;
	}
	
	public function getDeadline()
	{
		return ($this->deadline);
	}
	
	public function setDeadline($newDeadline)
	{
		$this->deadline = $newDeadline;
		$this->modified = true;
	}

	public function getNumNeeded()
	{
		return ($this->numNeeded);
	}
	
	public function setNumNeeded($newNumNeeded)
	{
		$this->numNeeded = $newNumNeeded;
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