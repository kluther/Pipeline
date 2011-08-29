<?php

class ProjectUser extends DbObject
{
	protected $userID;
	protected $projectID;
	protected $relationship;
	
	const DB_TABLE = 'project_user';
	
	const BANNED = 0;
	const FOLLOWER = 1;
	const ORGANIZER = 10;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'user_id' => 0,
			'project_id' => 0,
			'relationship' => null
		);	
		
		$args += $defaultArgs;
		
		$this->userID = $args['user_id'];
		$this->projectID = $args['project_id'];
		$this->relationship = $args['relationship'];
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
			'project_id' => $this->projectID,
			'relationship' => $this->relationship
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}

	
// ---------------------------------------------------------------------------- //	
	
	
	public static function changeRelationship($projectID=null, $userID=null, $relationship=null) {
		if( ($projectID === null) ||
			($userID === null) ||
			($relationship === null) ) {
			return null;
		}
		
		$query = "UPDATE ".self::DB_TABLE;
		$query .= " SET relationship = ".$relationship;
		$query .= " WHERE user_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		
		$db = Db::instance();
		$db->execute($query);
	}
	
	public static function delete($projectID=null, $userID=null) {
		if( ($projectID === null) ||
			($userID === null) ) {
			return null;
		}
		
		$query = "DELETE from ".self::DB_TABLE;
		$query .= " WHERE user_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		
		$db = Db::instance();
		$db->execute($query);
		//ObjectCache::remove('User',$this->userID;
	}
	
	// doesn't really belong here but what the hey
	public static function isCreator($userID=null, $projectID=null)
	{
		if( ($userID == null) || ($projectID == null) ) return null;
		
		$project = Project::load($projectID);
		if($project->getCreatorID() == $userID)
			return true;
		else
			return false;
	}
	
	// doesn't really belong here but what the hey
	public static function isContributor($userID=null, $projectID=null)
	{
		return (Event::isContributor($userID, $projectID));
	}
	
	public static function isOrganizer($userID=null, $projectID=null)
	{
		return (self::hasRelationship($userID,$projectID,self::ORGANIZER));
	}
	
	public static function isFollower($userID=null, $projectID=null)
	{
		return (self::hasRelationship($userID,$projectID,self::FOLLOWER));
	}
	
	public static function isBanned($userID=null, $projectID=null)
	{
		return (self::hasRelationship($userID,$projectID,self::BANNED));
	}
	
	// avoid calling this... use one of the aliased functions above instead
	public static function hasRelationship($userID=null, $projectID=null, $relationship=null)
	{
		if( ($userID == null) || ($projectID == null) || ($relationship == null) ) return null;
		
		$query = "SELECT * FROM ".self::DB_TABLE;
		$query .= sprintf(" WHERE user_id = '%s' AND project_id = '%s' AND relationship = '%s'",
				mysql_real_escape_string($userID),
				mysql_real_escape_string($projectID),
				mysql_real_escape_string($relationship)
			);
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result))
			return false;
		else
			return true;
	}
	
// ---------------------------------------------------------------------------- //
	
	public static function getBanned($projectID=null)
	{
		return (self::getProjectUsersByRelationship($projectID,self::BANNED));
	}
	
	public static function getFollowers($projectID=null)
	{
		return (self::getProjectUsersByRelationship($projectID,self::FOLLOWER));
	}	
	
	public static function getOrganizers($projectID=null)
	{
		return (self::getProjectUsersByRelationship($projectID,self::ORGANIZER));
	}	
	
	// avoid calling this... use one of the aliased functions above instead
	public static function getProjectUsersByRelationship($projectID=null, $relationship=null)
	{
		if( ($projectID == null) || ($relationship === null) ) return null;
		
		$query = "SELECT pu.user_id FROM ".self::DB_TABLE." pu";
		$query .= " INNER JOIN ".User::DB_TABLE." u ON ";
		$query .= " pu.user_id = u.id";
		$query .= sprintf(" WHERE pu.project_id = '%s' AND pu.relationship = '%s'",
				mysql_real_escape_string($projectID),
				mysql_real_escape_string($relationship)
			);
		$query .= " ORDER BY u.username ASC"; // alphabetical
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$users = array();
		while($row = mysql_fetch_assoc($result))
			$users[$row['user_id']] = User::load($row['user_id']);		
		return $users;
	}
	
	// --- only getters and setters below here --- //	
	
	public function getUserID()
	{
		return ($this->userID);
	}
	
	public function setUserID($newUserID)
	{
		$this->userID = $newUserID;
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
	
	public function getRelationship()
	{
		return ($this->relationship);
	}
	
	public function setRelationship($newRelationship)
	{
		$this->relationship = $newRelationship;
		$this->modified = true;
	}
}