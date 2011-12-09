<?php

class Project extends DbObject
{
	protected $id;
	protected $creatorID;
	protected $title;
	protected $slug;
	protected $pitch;
	protected $specs;
	protected $rules;
	protected $status;
	protected $deadline;
	protected $venue;
	protected $isPrivate;
	protected $dateCreated;

	const DB_TABLE = 'project';
	
	const STATUS_PRE_PRODUCTION = 2;
	const STATUS_IN_PRODUCTION = 3;
	const STATUS_POST_PRODUCTION = 4;
	const STATUS_COMPLETED = 1;
	const STATUS_CANCELED = 0;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'creator_id' => 0,
			'title' => '',
			'slug' => '',
			'pitch' => '',
			'specs' => '',
			'rules' => '',			
			'status' => 0,
			'deadline' => null,
			'venue' => '',
			'private' => 0,
			'date_created' => null
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->creatorID = $args['creator_id'];
		$this->title = $args['title'];
		$this->slug = $args['slug'];
		$this->pitch = $args['pitch'];
		$this->specs = $args['specs'];
		$this->rules = $args['rules'];		
		$this->status = $args['status'];
		$this->deadline = $args['deadline'];
		$this->venue = $args['venue'];
		$this->isPrivate = $args['private'];
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
			'title' => $this->title,
			'slug' => $this->slug,
			'pitch' => $this->pitch,
			'specs' => $this->specs,
			'rules' => $this->rules,		
			'status' => $this->status,
			'deadline' => $this->deadline,
			'venue' => $this->venue,
			'private' => $this->isPrivate
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	public function isCreator($userID=null) {
		return(ProjectUser::isCreator($userID, $this->id));
	}	
	
	public function isTrusted($userID=null) {
		return(ProjectUser::isTrusted($userID, $this->id));
	}		
	
	public function isMember($userID=null) {
		return(ProjectUser::isMember($userID, $this->id));
	}
	
	public function isFollower($userID=null) {
		return(ProjectUser::isFollower($userID, $this->id));
	}
	
	public function isBanned($userID=null) {
		return(ProjectUser::isBanned($userID, $this->id));
	}	
	
	public function isAffiliated($userID=null) {
		return(ProjectUser::isAffiliated($userID, $this->id));
	}
	
	public function isInvited($userID=null) {
		return(Invitation::hasInvitations($userID, $this->id));
	}

	public function getAllMembers() {
		return(ProjectUser::getAllMembers($this->id));
	}
	
	public function getBanned() {
		return(projectUser::getBanned($this->id));
	}
	
	public function getFollowers() {
		return(ProjectUser::getFollowers($this->id));
	}		
	
	public function getTasks($status=null, $limit=null) {
		return(Task::getByProjectID($this->id, $status, $limit));
	}
	
	public function getInvitations($responded=null) {
		return(Invitation::getByProjectID($this->id, $responded));
	}
	
	public function getUnaffiliatedUsernames($term=null) {
		return(ProjectUser::getUnaffiliatedUsernames($this->id, $term));
	}
	
	public function getBannableUsernames($term=null) {
		return(ProjectUser::getBannableUsernames($this->id, $term));
	}
	
	public function getTrustedUsernames($term=null) {
		return(ProjectUser::getTrustedUsernames($this->id, $term));
	}	
	
	/* static methods */
	
	// used for admin page
	public static function getAllProjects($limit=null) {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " ORDER BY date_created DESC, title ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$projects = array();
		while($row = mysql_fetch_assoc($result))
			$projects[$row['id']] = self::load($row['id']);
		return $projects;	
	}
	
	public static function getPublicProjects($userID=null, $limit=null) {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE (private = 0)";
		// don't include projects the user is affiliated with
		if(!empty($userID)) {
			$query .= " AND id NOT IN (";
				$query .= " SELECT project_id FROM ".ProjectUser::DB_TABLE;
				$query .= " WHERE user_id = ".$userID;
			$query .= " )";
		}
		$query .= " ORDER BY status DESC, ISNULL(deadline) ASC, title ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$projects = array();
		while($row = mysql_fetch_assoc($result))
			$projects[$row['id']] = self::load($row['id']);
		return $projects;	
	}
	
	// used on profile page
// 	public static function getByUserID($userID=null, $private=null, $limit=null) {
// 		if($userID === null) return null;
// 		
// 		$query = "SELECT id FROM ".self::DB_TABLE;
// 		$query .= " WHERE 
// 		// creator
// 		$query .= " WHERE (creator_id = ".$userID;
// 		// follower, organizer
// 		$query .= " OR id IN (";
// 			$query .= " SELECT DISTINCT project_id FROM ".ProjectUser::DB_TABLE;
// 			$query .= " WHERE user_id = ".$userID;
// 			$query .= " AND relationship != ".ProjectUser::BANNED;
// 		$query .= " )";
// 		// contributor
// 		$query .= " OR id IN (";
// 			$query .= " SELECT DISTINCT project_id FROM ".Accepted::DB_TABLE;
// 			$query .= " WHERE creator_id = ".$userID;
// 			//$query .= " OR user_2_id = ".$userID;
// 		$query .= "))";
// 		// not banned
// 		$query .= " AND id NOT IN (";
// 			$query .= " SELECT DISTINCT project_id FROM ".ProjectUser::DB_TABLE;
// 			$query .= " WHERE user_id = ".$userID;
// 			$query .= " AND relationship = ".ProjectUser::BANNED;
// 		$query .= " )";
// 		if($private === true) {
// 			$query .= " AND private=1";
// 		} elseif($private === false) {
// 			$query .= " AND private=0";
// 		}
// 		$query .= " ORDER BY ISNULL(deadline) ASC, title ASC";
// 		//echo $query;
// 		if($limit != null)
// 			$query .= " LIMIT ".$limit;
// 			
// 		$db = Db::instance();
// 		$result = $db->lookup($query);
// 		if(!mysql_num_rows($result)) return null;
// 		
// 		$projects = array();
// 		while($row = mysql_fetch_assoc($result))
// 			$projects[$row['id']] = self::load($row['id']);
// 		return $projects;
// 	}
	
	public static function getProjectFromSlug($slug=null)
	{
		if($slug == null) return null;
		
		$query  = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE slug = '".$slug."'";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$row = mysql_fetch_assoc($result);
		$project = Project::load($row['id']);
		return $project;
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
	
	public function getCreator()
	{
		return (User::load($this->creatorID));
	}

	public function setCreatorID($newCreatorID)
	{
		$this->creatorID = $newCreatorID;
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
	
	public function getSlug()
	{
		return ($this->slug);
	}
	
	public function setSlug($newSlug)
	{
		$this->slug = $newSlug;
		$this->modified = true;
	}

	public function getPitch()
	{
		return ($this->pitch);
	}

	public function setPitch($newPitch)
	{
		$this->pitch = $newPitch;
		$this->modified = true;
	}	
	
	public function getSpecs()
	{
		return ($this->specs);
	}
	
	public function setSpecs($newSpecs)
	{
		$this->specs = $newSpecs;
		$this->modified = true;
	}
	
	public function getRules()
	{
		return ($this->rules);
	}
	
	public function setRules($newRules)
	{
		$this->rules = $newRules;
		$this->modified = true;
	}
	
	public function getStatus()
	{
		return ($this->status);
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
	
	public function getVenue()
	{
		return ($this->venue);
	}	
	
	public function setVenue($newVenue)
	{
		$this->venue = $newVenue;
		$this->modified = true;
	}	
	
	public function getPrivate()
	{
		return ($this->isPrivate);
	}
	
	public function setPrivate($newIsPrivate)
	{
		$this->isPrivate = $newIsPrivate;
		$this->modified = true;
	}
	
	public function getDateCreated()
	{
		return ($this->dateCreated);
	}
	
	public function setDateCreated($newDateCreated)
	{
		$this->dateCreated = $newDateCreated;
		$this->modified = true;
	}
}