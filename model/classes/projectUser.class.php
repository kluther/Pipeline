<?php

class ProjectUser extends DbObject
{
	protected $id;
	protected $userID;
	protected $projectID;
	protected $relationship;
	protected $trusted;
	
	const DB_TABLE = 'project_user';
	
	const BANNED = 0;
	const FOLLOWER = 1;
	const CONTRIBUTOR = 5;
	const CREATOR = 10;
	
	const TRUSTED = 1;
	const UNTRUSTED = 0;
	//const ORGANIZER = 10;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'user_id' => 0,
			'project_id' => 0,
			'relationship' => 0,
			'trusted' => 0
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->userID = $args['user_id'];
		$this->projectID = $args['project_id'];
		$this->relationship = $args['relationship'];
		$this->trusted = $args['trusted'];
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
			'relationship' => $this->relationship,
			'trusted' => $this->trusted
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	public function delete() {
		$query = "DELETE from ".self::DB_TABLE;
		$query .= " WHERE user_id = ".$this->userID;
		$query .= " AND project_id = ".$this->projectID;
		
		$db = Db::instance();
		$db->execute($query);
		ObjectCache::remove(get_class($this),$this->id);
	}

	
// ---------------------------------------------------------------------------- //	
	
	
	public static function find($userID=null, $projectID=null) {
		if( ($userID === null) ||
			($projectID === null) ) {
			return null;
		}
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE user_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result))
			return null;
		elseif($row = mysql_fetch_assoc($result))
			return (self::load($row['id']));
	}
	
	// public static function changeRelationship($projectID=null, $userID=null, $relationship=null) {
		// if( ($projectID === null) ||
			// ($userID === null) ||
			// ($relationship === null) ) {
			// return null;
		// }
		
		// $query = "UPDATE ".self::DB_TABLE;
		// $query .= " SET relationship = ".$relationship;
		// $query .= " WHERE user_id = ".$userID;
		// $query .= " AND project_id = ".$projectID;
		
		// $db = Db::instance();
		// $db->execute($query);
	// }
	
	
	public static function isTrusted($userID=null, $projectID=null) {
		if( ($userID === null) || ($projectID === null) ) return null;
		
		$query = "SELECT * FROM ".self::DB_TABLE;
		$query .= " WHERE user_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		$query .= " AND trusted = ".self::TRUSTED;
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result))
			return false;
		else
			return true;	
	}		
	
	public static function getTrustedContributors($projectID=null) {
		return(self::getContributors($projectID, self::TRUSTED));
	}
	
	public static function getUntrustedContributors($projectID=null) {
		return(self::getContributors($projectID, self::UNTRUSTED));
	}	
	
	public static function getContributors($projectID=null, $trusted=null) {
		return(self::getByProjectID($projectID, self::CONTRIBUTOR, $trusted));
	}
	
	public static function getTrustedFollowers($projectID=null) {
		return(self::getFollowers($projectID, self::TRUSTED));
	}
	
	public static function getUntrustedFollowers($projectID=null) {
		return(self::getFollowers($projectID, self::UNTRUSTED));
	}	
	
	public static function getFollowers($projectID=null, $trusted=null) {
		return(self::getByProjectID($projectID, self::FOLLOWER, $trusted));
	}

	public static function getBanned($projectID=null) {
		return(self::getByProjectID($projectID, self::BANNED));
	}
	
	public static function getBannableUsernames($projectID=null) {
		if($projectID === null) return null;
		
		$query = "SELECT username FROM ".User::DB_TABLE;
		$query .= " WHERE id NOT IN (";
			$query .= " SELECT user_id FROM ".self::DB_TABLE;
			$query .= " WHERE project_id = ".$projectID;
			$query .= " AND relationship = ".self::BANNED; // can't be banned
			$query .= " OR relationship = ".self::CREATOR; // can't be creator
		$query .= " )";
		$query .= " ORDER BY username ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();
		
		$usernames = array();
		while($row = mysql_fetch_assoc($result))
			$usernames[] = $row['username'];
		return $usernames;		
	}
	
	public static function getTrustedUsernames($projectID=null) {
		if($projectID === null) return null;
		
		$query = "SELECT u.username AS username FROM ".User::DB_TABLE." u";	
		$query .= " INNER JOIN ".self::DB_TABLE." pu";
		$query .= " ON u.id = pu.user_id";
		$query .= " WHERE pu.project_id = ".$projectID;
		$query .= " AND pu.trusted = ".self::TRUSTED;
		$query .= " ORDER BY u.username ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		
		if(!mysql_num_rows($result))
			return array();
		
		$usernames = array();
		while($row = mysql_fetch_assoc($result))
			$usernames[] = $row['username'];
		return $usernames;			
	}	
	
	public static function getUnaffiliatedUsernames($projectID=null) {
		if($projectID === null) return null;
		
		$query = "SELECT username FROM ".User::DB_TABLE;	
		$query .= " WHERE id NOT IN (";
			$query .= " SELECT user_id FROM ".self::DB_TABLE;
			$query .= " WHERE project_id = ".$projectID;
		$query .= " )";
		$query .= " ORDER BY username ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		
		if(!mysql_num_rows($result))
			return array();
		
		$usernames = array();
		while($row = mysql_fetch_assoc($result))
			$usernames[] = $row['username'];
		return $usernames;			
	}		
	
	public static function getByProjectID($projectID=null, $relationship=null, $trusted=null) {
		if($projectID == null) return null;
		
		$query = "SELECT user_id AS id FROM ".self::DB_TABLE." pu";
		$query .= " INNER JOIN ".User::DB_TABLE." u ON ";
		$query .= " pu.user_id = u.id";
		$query .= " WHERE pu.project_id = ".$projectID;
		if($relationship !== null) {
			$query .= " AND pu.relationship = ".$relationship;
		}
		if($trusted !== null) {
			$query .= " AND pu.trusted = ".$trusted;
		}
		$query .= " ORDER BY u.username ASC";	
		//echo $query.'<br />';
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$users = array();
		while($row = mysql_fetch_assoc($result))
			$users[$row['id']] = User::load($row['id']);
		return $users;			
	}
	
	// doesn't really belong here but what the hey
	// public static function isCreator($userID=null, $projectID=null)
	// {
		// if( ($userID == null) || ($projectID == null) ) return null;
		
		// $project = Project::load($projectID);
		// if($project->getCreatorID() == $userID)
			// return true;
		// else
			// return false;
	// }
	
	// doesn't really belong here but what the hey
	// public static function isContributor($userID=null, $projectID=null) {
		// return (Accepted::hasAccepted($userID, $projectID));
	// }
	
	// public static function isOrganizer($userID=null, $projectID=null)
	// {
		// return (self::hasRelationship($userID,$projectID,self::ORGANIZER));
	// }

	public static function isCreator($userID=null, $projectID=null) {
		return (self::hasRelationship($userID,$projectID,self::CREATOR));	
	}
	
	public static function isContributor($userID=null, $projectID=null) {
		return (self::hasRelationship($userID,$projectID,self::CONTRIBUTOR));
	}
	
	public static function isFollower($userID=null, $projectID=null)
	{
		return (self::hasRelationship($userID,$projectID,self::FOLLOWER));
	}
	
	public static function isBanned($userID=null, $projectID=null)
	{
		return (self::hasRelationship($userID,$projectID,self::BANNED));
	}

	public static function isAffiliated($userID=null, $projectID=null) {
		return (self::hasRelationship($userID,$projectID));
	}
	
	// public static function isAffiliated($userID=null, $projectID=null) {
		// if (self::hasRelationship($userID, $projectID) ||
			// self::isCreator($userID, $projectID) ||
			// self::isContributor($userID, $projectID) )
			// return true;
		// else return false;
	// }
	
	// avoid calling this... use one of the aliased functions above instead
	public static function hasRelationship($userID=null, $projectID=null, $relationship=null) {
		if( ($userID === null) || ($projectID === null) ) return null;
		
		$query = "SELECT * FROM ".self::DB_TABLE;
		$query .= " WHERE user_id = ".$userID;
		$query .= " AND project_id = ".$projectID;
		if($relationship !== null)
			$query .= " AND relationship = ".$relationship;
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result))
			return false;
		else
			return true;
	}
	
// ---------------------------------------------------------------------------- //
	
	// public static function getBanned($projectID=null)
	// {
		// return (self::getUsersByRelationship($projectID,self::BANNED));
	// }
	
	// public static function getFollowers($projectID=null)
	// {
		// return (self::getUsersByRelationship($projectID,self::FOLLOWER));
	// }	
	
	// public static function getContributors($projectID=null) {
	// //	return (self::getUsersByRelationship($projectID,self::CONTRIBUTOR));
		// return (Accepted::getUsersByProjectID($projectID));
	// }	
	
	// get users who have accepted any task in this project
	// public static function getOnlyContributors($projectID=null, $limit=null) {
		// if($projectID == null) return null;
		// $project = Project::load($projectID);
		// $projectCreator = $project->getCreator();
		
		// $query = "SELECT creator_id AS id FROM ".Accepted::DB_TABLE;
		// $query .= " WHERE project_id = ".$projectID;
		// $query .= " AND status != ".Accepted::STATUS_RELEASED;
		// $query .= " AND creator_id NOT IN (";
			// $query .= " SELECT user_id FROM ".self::DB_TABLE;
			// $query .= " WHERE project_id = ".$projectID;
			// $query .= " AND relationship != ".self::FOLLOWER;
		// $query .= " )";
		// $query .= " AND creator_id != ".$projectCreator->getID();
		// $query .= " ORDER BY status DESC, date_created DESC";
		// if($limit != null)
			// $query .= " LIMIT ".$limit;
			
		// $db = Db::instance();
		// $result = $db->lookup($query);
		// if(!mysql_num_rows($result)) return array();

		// $users = array();
		// while($row = mysql_fetch_assoc($result))
			// $users[$row['id']] = User::load($row['id']);
		// return $users;	
	// }
	
	// public static function getOrganizers($projectID=null)
	// {
		// return (self::getUsersByRelationship($projectID,self::ORGANIZER));
	// }	
	
	// avoid calling this... use one of the aliased functions above instead
	// public static function getUsersByRelationship($projectID=null, $relationship=null) {
		// if($projectID == null) return null;
		// $project = Project::load($projectID);
		// $projectCreator = $project->getCreator();		
		
		// $query = "SELECT pu.user_id AS user_id FROM ".self::DB_TABLE." pu";
		// $query .= " INNER JOIN ".User::DB_TABLE." u ON ";
		// $query .= " pu.user_id = u.id";
		// $query .= " WHERE pu.project_id = ".$projectID;
		// $query .= " AND pu.user_id NOT IN (";
			// $query .= "SELECT creator_id FROM ".Accepted::DB_TABLE;
			// $query .= " WHERE project_id = ".$projectID;
			// $query .= " AND status != ".Accepted::STATUS_RELEASED;			
		// $query .= " )";
		// $query .= " AND pu.user_id != ".$projectCreator->getID();		
		// if($relationship !== null)
			// $query .= " AND pu.relationship = ".$relationship;
		// $query .= " ORDER BY u.username ASC"; // alphabetical
		// //echo $query;
		
		// $db = Db::instance();
		// $result = $db->lookup($query);
		// if(!mysql_num_rows($result)) return array();

		// $users = array();
		// while($row = mysql_fetch_assoc($result))
			// $users[$row['user_id']] = User::load($row['user_id']);		
		// return $users;
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
	
	public function getTrusted() {
		return ($this->trusted);
	}
	
	public function setTrusted($newTrusted) {
		$this->trusted = $newTrusted;
		$this->modified = true;
	}
}