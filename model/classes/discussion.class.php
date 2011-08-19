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
			'deleted' => 0
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
		
		$discussion = self::load($id);
		$result = $discussion->delete();
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
	
	// ---Static Methods--- //
	
	// public static function getVersionComments($versionID=null, $limit=null, $deleted=false)
	// {
		// if($versionID == null) return null;
		
		// $query = " SELECT id FROM ".self::DB_TABLE;
		// $query .= " WHERE node_id = ".$versionID;
		// $query .= " AND parent_id IS NULL";
		// if($deleted !== null)
		// {
			// if($deleted===true)
				// $query .= " AND node_id IN (";
			// else
				// $query .= " AND node_id NOT IN (";
				
			// $query .= " SELECT id FROM ".Version::DB_TABLE;
			// $query .= " WHERE deleted=1";
			// $query .= " OR parent_id IN (";
				// $query .= " SELECT id FROM ".Submission::DB_TABLE;
				// $query .= " WHERE deleted=1";
				// $query .= " OR parent_id IN (";
					// $query .= " SELECT id FROM ".Scene::DB_TABLE;
					// $query .= " WHERE deleted=1";
				// $query .= ")";
			// $query .= " )";
			// $query .= " )";
				
			// if($deleted===true)
				// $query .= " OR deleted=1";
			// else
				// $query .= " AND deleted=0";
		// }		
		// $query .= " ORDER BY date_time_created DESC";
		// if($limit!=null)
			// $query .= " LIMIT ".$limit;	
		// //echo $query . "<br>";
		// $db = Db::instance();
		// $result = $db->lookup($query);
		
		// if (!mysql_num_rows($result)) {return null;}
		
		// $comments = array();
		// while ($row = mysql_fetch_assoc($result))
			// $comments[$row['id']] = self::load($row['id']);
		
		// return $comments;	
	// }
	
	// public static function getNumVersionComments($versionID=null, $deleted=false)
	// {
		// $comments = self::getVersionComments($versionID, null, $deleted);
		// return (count($comments));
	// }
	
	// /* returns both project-level discussions and submission comments */
	// public static function getRecentDiscussions($projectID=null, $limit=null, $deleted=false)
	// {
		// if ($projectID === null) return null;
		
		// $db = Db::instance();
		// $projectID_s = $db->quoteString($projectID);
		
		// $query = "SELECT id FROM ".self::DB_TABLE." WHERE project_id=".$projectID_s;
		// if($deleted===true)
			// $query .= " AND deleted=1";
		// elseif($deleted===false)
			// $query .= " AND deleted=0";
		// $query .= " ORDER BY date_time_created DESC";
		// if($limit!=null)
			// $query .= " LIMIT ".$limit;		
		// //echo $query;
		// $result = $db->lookup($query);
		
		// if (!mysql_num_rows($result)) {return null;}
		
		// $discussions = array();
		// while ($row = mysql_fetch_assoc($result))
			// $discussions[$row['id']] = self::load($row['id']);
		
		// return $discussions;	
	// }

	
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

	public static function getByProjectID($projectID=null, $category=null, $limit=null, $deleted=false)
	{
		if ($projectID == null) return null;
		
		$query = "SELECT DISTINCT parent_id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id=".$projectID;
//		$query .= " AND parent_id IS NOT NULL";
		if($category != null)
			$query .= " AND category='".$category."'";
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
		
		$discussions = array();
		while ($row = mysql_fetch_assoc($result))
			$discussions[$row['parent_id']] = self::load($row['parent_id']);
		
		return $discussions;		
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
	
	// public static function getDiscussionChildren($discussionID=null, $deleted=false)
	// {
		// return self::getChildren($discussionID, null, $deleted);
	// }
	
	// public static function getNumDiscussionChildren($discussionID=null, $deleted=false)
	// {
		// $comments = self::getChildren($discussionID, null, $deleted);
		// return (count($comments));
	// }
	
	// public static function getCommentChildren($discussionID=null, $deleted=false)
	// {
		// return self::getChildren($discussionID, null, $deleted);
	// }
	
	// public static function getNumCommentChildren($discussionID=null, $deleted=false)
	// {
		// $comments = self::getChildren($discussionID, null, $deleted);
		// return (count($comments));
	// }
	
	
	// public static function getNumProjectDiscussions($projectID=null, $deleted=false)
	// {
		// $comments = self::getProjectDiscussion($projectID, null, $deleted);
		// return (count($comments));
	// }
	
	// public static function getUserDiscussions($userID=null, $projectID=null, $limit=null, $deleted=false)
	// {
		// if($userID==null) return null;
		
		// $query = " SELECT id FROM ".self::DB_TABLE;
		// $query .= " WHERE creator_id = ".$userID;
		// $query .= ($projectID != null) ? " AND project_id =".$projectID : "";
		// if($deleted !== null)
		// {
			// if($deleted===true)
				// $query .= " AND node_id IN (";
			// elseif($deleted===false)
				// $query .= " AND node_id NOT IN (";
				
			// $query .= " SELECT id FROM ".Version::DB_TABLE;
			// $query .= " WHERE deleted=1";
			// $query .= " OR parent_id IN (";
				// $query .= " SELECT id FROM ".Submission::DB_TABLE;
				// $query .= " WHERE deleted=1";
				// $query .= " OR parent_id IN (";
					// $query .= " SELECT id FROM ".Scene::DB_TABLE;
					// $query .= " WHERE deleted=1";
				// $query .= ")";
			// $query .= " )";
			// $query .= " )";
				
			// if($deleted===true)
				// $query .= " OR deleted=1";
			// elseif($deleted===false)
				// $query .= " AND deleted=0";
		// }		
		// $query .= " ORDER BY date_time_created DESC";
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
	
	// public static function getNumUserDiscussions($userID=null, $projectID=null, $deleted=false)
	// {
		// $discussions = self::getUserDiscussions($userID, $projectID, null, $deleted);
		// return (count($discussions));
	// }
	
	// public static function getLastDiscussionByUser($userID=null, $projectID=null, $deleted=false)
	// {
		// $discussions = self::getUserDiscussions($userID, $projectID, 1, $deleted);
		// if($discussions == null)
			// return null;
		// else
			// return (reset($discussions));
	// }	
	
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
}
