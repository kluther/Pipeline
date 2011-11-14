<?php

class Discussion extends DbObject
{
	protected $id;
	protected $creatorID;
	protected $projectID;
	protected $parentID;
	protected $title;
	protected $message;
	protected $category;
	protected $deleted;
	protected $locked;
	protected $dateCreated;
		
	const  DB_TABLE = 'discussion';
	
	// const CATEGORY_ACTIVITY = 1;
	// const CATEGORY_DETAILS = 2;
	// const CATEGORY_TASKS = 3;
	// const CATEGORY_PEOPLE = 4;
	
	public function __construct($args = array())
	{
		$defaultArgs = array
		(
			'id' => null,
			'creator_id' => 0,
			'project_id' => 0,
			'parent_id' => null,
			'title' => '',
			'message' => '',
			'category' => null,
			'date_created' => null,
			'deleted' => 0,
			'locked' => 0
		);
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['creator_id'];
		$this->projectID = $args['project_id'];
		$this->parentID = $args['parent_id'];
		$this->title = $args['title'];
		$this->message = $args['message'];
		$this->category = $args['category'];
		$this->dateCreated = $args['date_created'];
		$this->deleted = $args['deleted'];
		$this->locked = $args['locked'];
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
			'parent_id' => $this->parentID,
			'title' => $this->title,
			'message' => $this->message,
			'category' => $this->category,
			'deleted' => $this->deleted,
			'locked' => $this->locked
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
		
		$discussion = self::load($id);
		$result = $discussion->delete();
		return $result;
	}

	// ---Static Methods--- //
	
	public static function getBasicsDiscussionsByProjectID($projectID=null, $limit=null) {
		return (self::getByProjectID($projectID, BASICS_ID, $limit));
	}	
	
	public static function getTasksDiscussionsByProjectID($projectID=null, $limit=null) {
		return (self::getByProjectID($projectID, TASKS_ID, $limit));
	}	
	
	public static function getPeopleDiscussionsByProjectID($projectID=null, $limit=null) {
		return (self::getByProjectID($projectID, PEOPLE_ID, $limit));
	}	
	
	public static function getActivityDiscussionsByProjectID($projectID=null, $limit=null) {
		return (self::getByProjectID($projectID, ACTIVITY_ID, $limit));
	}
	
	public static function getByUserID($userID=null, $projectID=null, $limit=null, $deleted=false) {
		if ($userID == null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE creator_id=".$userID;
		$query .= " AND parent_id = id";
		if($projectID != null)
			$query .= " AND project_id=".$projectID;
		$query .= " AND parent_id = id";
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		//$query .= " GROUP BY parent_id";				
		$query .= " ORDER BY locked ASC, date_created DESC, title ASC";	
		if($limit!=null)
			$query .= " LIMIT ".$limit;		
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);		
		if (!mysql_num_rows($result)) {return null;}
		
		$discussions = array();
		while ($row = mysql_fetch_assoc($result))
			$discussions[$row['id']] = self::load($row['id']);
		
		return $discussions;		
	}
	
	// public static function getMoreDiscussions($userID=null, $projectID=null, $limit=null, $deleted=false) {
		// if ( ($userID == null) || ($projectID == null) ) return null;
		
		// $query = "SELECT id FROM ".self::DB_TABLE;
		// $query .= " WHERE parent_id NOT IN (";
			// $query .= " SELECT id FROM ".self::DB_TABLE;
			// $query .= " WHERE creator_id = ".$userID;
			// $query .= " AND project_id = ".$projectID;
		// $query .= " )";
		// $query .= " AND project_id=".$projectID;
		// $query .= " AND parent_id = id";
		// if($deleted===true)
			// $query .= " AND deleted=1";
		// elseif($deleted===false)
			// $query .= " AND deleted=0";
		// $query .= " GROUP BY parent_id";
		// $query .= " ORDER BY locked ASC, date_created DESC";
		// if($limit!=null)
			// $query .= " LIMIT ".$limit;		
		// //echo $query;
		
		// $db = Db::instance();
		// $result = $db->lookup($query);		
		// if (!mysql_num_rows($result)) {return null;}
		
		// $discussions = array();
		// while ($row = mysql_fetch_assoc($result))
			// $discussions[$row['id']] = self::load($row['id']);
		
		// return $discussions;		
	// }

	public static function getByProjectID($projectID=null, $category=null, $limit=null, $deleted=false)
	{
		if ($projectID == null) return null;
		
		$query = "SELECT parent_id AS id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id=".$projectID;
//		$query .= " AND parent_id IS NOT NULL";
		if($category != null)
			$query .= " AND category='".$category."'";
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		//$query .= " GROUP BY parent_id";
		$query .= " ORDER BY locked ASC, date_created DESC, title ASC";
		if($limit!=null)
			$query .= " LIMIT ".$limit;		
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);		
		if (!mysql_num_rows($result)) {return null;}
		
		$discussions = array();
		while ($row = mysql_fetch_assoc($result))
			$discussions[$row['id']] = self::load($row['id']);
		
		return $discussions;		
	}
	
	// for discussions template
	public function getLastReply() {
		$lastReply = $this->getReplies("DESC", 1, false);
		if(!empty($lastReply)) {
			$lastReply = array_pop($lastReply);
			return ($lastReply);
		} else {
			return null;
		}
	}
	
	public function getReplies($sort="DESC", $limit=null, $deleted=false)
	{
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE parent_id=".$this->getID();
		$query .= " AND id != parent_id"; // ignore parent discussion
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		$query .= " ORDER BY date_created ".$sort;
		if($limit!=null)
			$query .= " LIMIT ".$limit;		
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if (!mysql_num_rows($result)) {return null;}
		
		$replies = array();
		while ($row = mysql_fetch_assoc($result))
			$replies[$row['id']] = self::load($row['id']);
		return $replies;
	}
	
	// for email notifications
	public function getDistinctRepliers($deleted=false) {
		$query = "SELECT DISTINCT creator_id AS id FROM ".self::DB_TABLE;
		$query .= " WHERE parent_id=".$this->getID();
		$query .= " AND id != parent_id"; // ignore parent discussion
		if($deleted===true)
			$query .= " AND deleted=1";
		elseif($deleted===false)
			$query .= " AND deleted=0";
		$query .= " ORDER BY date_created DESC";
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if (!mysql_num_rows($result)) {return null;}
		
		$repliers = array();
		while ($row = mysql_fetch_assoc($result))
			$repliers[$row['id']] = User::load($row['id']);
		return $repliers;	
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
	
	public function getTitle()
	{
		return ($this->title);
	}
	
	public function setTitle($newTitle)
	{
		$this->title = $newTitle;
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
	
	public function getCategory()
	{
		return ($this->category);
	}
	
	public function setCategory($newCategory)
	{
		$this->category = $newCategory;
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
	
	public function getLocked() {
		return ($this->locked);
	}
	
	public function setLocked($newLocked) {
		$this->locked = $newLocked;
		$this->modified = true;
	}
}
