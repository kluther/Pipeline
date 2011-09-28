<?php

class Invitation extends DbObject
{
	protected $id;
	protected $inviterID;
	protected $inviteeID;
	protected $inviteeEmail;
	protected $projectID;
	protected $trusted;
	protected $invitationMessage;
	protected $response;
	protected $responseMessage;
	protected $dateResponded;
	protected $dateCreated;

	const DB_TABLE = 'invitation';
	
	const ACCEPTED = 2;
	const DECLINED = 1;
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'inviter_id' => 0,
			'invitee_id' => null,
			'invitee_email' => null,
			'project_id' => 0,
			'trusted' => 0,
			'invitation_message' => null,
			'response' => null,
			'response_message' => null,
			'date_responded' => null,
			'date_created' => null
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->inviterID = $args['inviter_id'];
		$this->inviteeID = $args['invitee_id'];
		$this->inviteeEmail = $args['invitee_email'];
		$this->projectID = $args['project_id'];
		$this->trusted = $args['trusted'];
		$this->invitationMessage = $args['invitation_message'];
		$this->response = $args['response'];
		$this->responseMessage = $args['response_message'];
		$this->dateResponded = $args['date_responded'];
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
			'inviter_id' => $this->inviterID,
			'invitee_id' => $this->inviteeID,
			'invitee_email' => $this->inviteeEmail,
			'project_id' => $this->projectID,
			'trusted' => $this->trusted,
			'invitation_message' => $this->invitationMessage,
			'response' => $this->response,
			'response_message' => $this->responseMessage,
			'date_responded' => $this->dateResponded
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	/* ------------------------------------------------------------------*/
	
	
	/* static methods */
	
	public static function getByUserID($userID=null, $projectID=null, $responded=null) {
		if($userID == null) return null;
		
		$query = " SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE invitee_id = ".$userID;
		if($responded===true)
			$query .= " AND response IS NOT NULL";
		elseif($responded===false)
			$query .= " AND response IS NULL";
		if($projectID !== null)
			$query .= " AND project_id = ".$projectID;
		$query .= " ORDER BY date_responded DESC, date_created DESC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$invitations = array();
		while($row = mysql_fetch_assoc($result))
			$invitations[$row['id']] = self::load($row['id']);
		return $invitations;	
	}
	
	public static function hasInvitations($userID=null, $projectID=null) {
		$invitations = self::getByUserID($userID, $projectID, $responded=false);
		if(!empty($invitations))
			return true;
		else
			return false;
	}
	
	
	public static function findByEmail($email=null) {
		if($email === null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE invitee_email = '".$email."'";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		if($row = mysql_fetch_assoc($result))
			return (self::load($row['id']));
		else
			return null;
	}	
	
	public static function linkByEmail($email=null, $userID=null) {
		if( ($email === null) || ($userID === null) ) return null;
		
		$query = "UPDATE ".self::DB_TABLE;
		$query .= " SET invitee_id = ".$userID;
		$query .= " WHERE invitee_email = '".$email."'";
		echo $query;
		
		$db = Db::instance();
		$db->execute($query);
	}
	
	// public static function getByTaskID($taskID=null, $responded=null) {
		// if($taskID == null) return null;
		
		// $query = " SELECT id FROM ".self::DB_TABLE;
		// $query .= " WHERE task_id = ".$taskID;
		// if($responded===true)
			// $query .= " AND response IS NOT NULL";
		// elseif($responded===false)
			// $query .= " AND response IS NULL";			
		// $query .= " ORDER BY ISNULL(date_responded) ASC";
		
		// $db = Db::instance();
		// $result = $db->lookup($query);
		// if(!mysql_num_rows($result)) return array();

		// $invitations = array();
		// while($row = mysql_fetch_assoc($result))
			// $invitations[$row['id']] = self::load($row['id']);
		// return $invitations;			
	// }	
	
	public static function getByProjectID($projectID=null, $responded=null) {
		if($projectID == null) return null;
		
		$query = " SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE project_id = ".$projectID;
		if($responded===true)
			$query .= " AND response IS NOT NULL";
		elseif($responded===false)
			$query .= " AND response IS NULL";			
		$query .= " ORDER BY ISNULL(date_responded) ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$invitations = array();
		while($row = mysql_fetch_assoc($result))
			$invitations[$row['id']] = self::load($row['id']);
		return $invitations;			
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
	
	public function getInviterID() {
		return ($this->inviterID);
	}
	
	public function setInviterID($newInviterID) {
		$this->inviterID = $newInviterID;
		$this->modified = true;
	}
	
	public function getInviteeID() {
		return ($this->inviteeID);
	}
	
	public function setInviteeID($newInviteeID) {
		$this->inviteeID = $newInviteeID;
		$this->modified = true;
	}
	
	public function getInviteeEmail() {
		return ($this->inviteeEmail);
	}
	
	public function setInviteeEmail($newInviteeEmail) {
		$this->inviteeEmail = $newInviteeEmail;
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
	
	public function getTrusted() {
		return ($this->trusted);
	}
	
	public function setTrusted($newTrusted) {
		$this->trusted = $newTrusted;
		$this->modified = true;
	}
	
	public function getInvitationMessage() {
		return ($this->invitationMessage);
	}
	
	public function setInvitationMessage($newInvitationMessage) {
		$this->invitationMessage = $newInvitationMessage;
		$this->modified = true;
	}
	
	public function getResponse() {
		return ($this->response);
	}
	
	public function setResponse($newResponse) {
		$this->response = $newResponse;
		$this->modified = true;
	}
	
	public function getResponseMessage() {
		return ($this->responseMessage);
	}
	
	public function setResponseMessage($newResponseMessage) {
		$this->responseMessage = $newResponseMessage;
		$this->modified = true;
	}

	public function getDateResponded()
	{
		return ($this->dateResponded);
	}
	
	public function setDateResponded($newDateResponded)
	{
		$this->dateResponded = $newDateResponded;
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