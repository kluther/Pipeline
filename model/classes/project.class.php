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
	
	public function getOrganizers()
	{
		return(ProjectUser::getOrganizers($this->id));
	}
	
	public function getContributors()
	{
		return (Event::getContributorsByProjectID($this->id));
	}
	
	public function getFollowers()
	{
		return(ProjectUser::getFollowers($this->id));
	}
	
	public function getBanned()
	{
		return(ProjectUser::getBanned($this->id));
	}
	
	public function getTasks($status=null, $limit=null) {
		return(Task::getByProjectID($this->id, $status, $limit));
	}
	
	/* static methods */
	
	public static function getLookingForHelp() {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE status > ".self::STATUS_COMPLETED;
		$query .= " AND id IN (";
			$query .= " SELECT DISTINCT project_id FROM ".Task::DB_TABLE;
			$query .= " WHERE status = 1";
		$query .= ") ";
		$query .= " ORDER BY deadline DESC";
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$projects = array();
		while($row = mysql_fetch_assoc($result))
			$projects[$row['id']] = self::load($row['id']);
		return $projects;
	}
	
	public static function getProjectFromSlug($slug=null)
	{
		if($slug == null) return null;
		
		$query  = "SELECT id FROM ".self::DB_TABLE;
		$query .= sprintf(" WHERE slug = '%s'",
				mysql_real_escape_string($slug)
			);
		
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