<?php

class User extends DbObject
{
	protected $id;
	protected $username;
	protected $password;
	protected $email;
	protected $name;
	protected $dob;
	protected $sex;
	protected $location;
	protected $biography;
	protected $picture;
	protected $pictureSmall;
	protected $pictureLarge;
	protected $themeID;
	protected $notifyCommentTaskLeading;
	protected $notifyEditTaskAccepted;
	protected $notifyCommentTaskAccepted;
	protected $notifyCommentTaskUpdate;
	protected $notifyInviteProject;
	protected $notifyTrustProject;
	protected $notifyBannedProject;
	protected $notifyDiscussionStarted;
	protected $notifyDiscussionReply;
	protected $notifyMakeTaskLeader;
	protected $notifyReceiveMessage;
	protected $notifyMassEmail;
	protected $admin;
	protected $dateCreated;
	protected $lastLogin;
	protected $secondLastLogin;
	
	const DB_TABLE = 'user';
	
	const PICTURE_LARGE_MAX_WIDTH = 48;
	const PICTURE_LARGE_MAX_HEIGHT = 48;
	const PICTURE_SMALL_MAX_WIDTH = 36;
	const PICTURE_SMALL_MAX_HEIGHT = 36;
	
	public function __construct($args = array())
	{
		$defaultArgs = array(
			'id' => null,
			'username' => '',
			'password' => '',
			'email' => '',
			'name' => null,
			'dob' => null,			
			'sex' => null,
			'location' => null,
			'biography' => null,
			'picture' => null,
			'picture_small' => null,
			'picture_large' => null,
			'theme_id' => 1,
			'notify_comment_task_leading' => 1,
			'notify_edit_task_accepted' => 1,
			'notify_comment_task_accepted' => 1,
			'notify_comment_task_update' => 1,
			'notify_invite_project' => 1,
			'notify_trust_project' => 1,
			'notify_banned_project' => 1,
			'notify_discussion_started' => 1,
			'notify_discussion_reply' => 1,
			'notify_make_task_leader' => 1,
			'notify_receive_message' => 1,
			'notify_mass_email' => 1,
			'admin' => 0,
			'date_created' => null,
			'last_login' => null,
			'second_last_login' => null
		);
		
		$args += $defaultArgs; // combine the arrays
		
		$this->id = $args['id'];
		$this->username = $args['username'];
		$this->password = $args['password'];
		$this->email = $args['email'];
		$this->name = $args['name'];
		$this->dob = $args['dob'];		
		$this->sex = $args['sex'];
		$this->location = $args['location'];
		$this->biography = $args['biography'];
		$this->picture = $args['picture'];
		$this->pictureSmall = $args['picture_small'];
		$this->pictureLarge = $args['picture_large'];
		$this->themeID = $args['theme_id'];
		$this->notifyCommentTaskLeading = $args['notify_comment_task_leading'];
		$this->notifyEditTaskAccepted = $args['notify_edit_task_accepted'];
		$this->notifyCommentTaskAccepted = $args['notify_comment_task_accepted'];
		$this->notifyCommentTaskUpdate = $args['notify_comment_task_update'];
		$this->notifyInviteProject = $args['notify_invite_project'];
		$this->notifyTrustProject = $args['notify_trust_project'];
		$this->notifyBannedProject = $args['notify_banned_project'];
		$this->notifyDiscussionStarted = $args['notify_discussion_started'];
		$this->notifyDiscussionReply = $args['notify_discussion_reply'];
		$this->notifyMakeTaskLeader = $args['notify_make_task_leader'];
		$this->notifyReceiveMessage = $args['notify_receive_message'];
		$this->notifyMassEmail = $args['notify_mass_email'];
		$this->admin = $args['admin'];
		$this->dateCreated = $args['date_created'];
		$this->lastLogin = $args['last_login'];
		$this->secondLastLogin = $args['second_last_login'];
	}
	
	public static function load($id)
	{
		$db = Db::instance();
		$obj = $db->fetch($id, __CLASS__, self::DB_TABLE);
		return $obj;
	}
	
	public static function loadByUsername($username)
	{
		$db = Db::instance();
			
		$query = sprintf("SELECT * FROM user WHERE username = '%s'",
				$username
			);
		$result = $db->lookup($query);
		
		if(!mysql_num_rows($result))
		{
			return null;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			$obj = new User($row);
			ObjectCache::set('User', $row['id'], $obj);
			return $obj;
		}		
	}
	
	public static function loadByEmail($email)
	{
		$db = Db::instance();
			
		$query = sprintf("SELECT * FROM user WHERE email = '%s'",
				$email
			);
		$result = $db->lookup($query);
		
		if(!mysql_num_rows($result))
		{
			return null;
		}
		else
		{
			$row = mysql_fetch_assoc($result);
			$obj = new User($row);
			ObjectCache::set('User', $row['id'], $obj);
			return $obj;
		}		
	}	

	public function save()
	{
		$db = Db::instance();
		// map database fields to class properties; omit id and dateCreated
		$db_properties = array(
			'username' => $this->username,
			'password' => $this->password,
			'email' => $this->email,
			'name' => $this->name,
			'dob' => $this->dob,			
			'sex' => $this->sex,
			'location' => $this->location,
			'biography' => $this->biography,
			'picture' => $this->picture,
			'picture_small' => $this->pictureSmall,
			'picture_large' => $this->pictureLarge,
			'theme_id' => $this->themeID,
			'notify_comment_task_leading' => $this->notifyCommentTaskLeading,
			'notify_edit_task_accepted' => $this->notifyEditTaskAccepted,
			'notify_comment_task_accepted' => $this->notifyCommentTaskAccepted,
			'notify_comment_task_update' => $this->notifyCommentTaskUpdate,
			'notify_invite_project' => $this->notifyInviteProject,
			'notify_trust_project' => $this->notifyTrustProject,
			'notify_banned_project' => $this->notifyBannedProject,
			'notify_discussion_started' => $this->notifyDiscussionStarted,
			'notify_discussion_reply' => $this->notifyDiscussionReply,
			'notify_make_task_leader' => $this->notifyMakeTaskLeader,
			'notify_receive_message' => $this->notifyReceiveMessage,
			'notify_mass_email' => $this->notifyMassEmail,
			'admin' => $this->admin,
			'last_login' => $this->lastLogin,
			'second_last_login' => $this->secondLastLogin
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}
	
	// static methods
	
	// used for admin page
	public static function getAllUsers($limit=null) {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " ORDER BY date_created DESC, username ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$users = array();
		while($row = mysql_fetch_assoc($result))
			$users[$row['id']] = self::load($row['id']);
		return $users;	
	}
	
	// used for admin page
	public static function getMassEmailAddresses($limit=500) {
		$query = "SELECT email FROM ".self::DB_TABLE;
		$query .= " WHERE notify_mass_email = 1";
		$query .= " ORDER BY username ASC";
		if($limit != null)
			$query .= " LIMIT ".$limit;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$emails = array();
		while($row = mysql_fetch_assoc($result))
			$emails[] = $row['email'];
		return $emails;	
	}
	
	// used for people search
	public static function getAllUsernames($term=null, $not=null) {	
		if($term === null) return null;
		
		$query = "SELECT username FROM ".User::DB_TABLE;
		$query .= " WHERE username LIKE '%".$term."%'";		
		if(!empty($not)) {
			$query .= " AND id != ".$not;
		}
		$query .= " ORDER BY username ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();
		
		$usernames = array();
		while($row = mysql_fetch_assoc($result))
			$usernames[] = $row['username'];
		return $usernames;		
	}		
	
	public static function getPossibleContributorUsernames($projectID=null) {
		if($projectID === null) return null;
		$project = Project::load($projectID);
		$creatorID = $project->getCreatorID();
		
		$query = "SELECT username FROM ".User::DB_TABLE;	
		// not banned
		$query .= " WHERE id NOT IN (";
			$query .= " SELECT user_id FROM ".ProjectUser::DB_TABLE;
			$query .= " WHERE project_id = ".$projectID;
			$query .= " AND relationship = ".ProjectUser::BANNED;
		$query .= " )";
		// not already a contributor
		$query .= " AND id NOT IN (";
			$query .= " SELECT creator_id FROM ".Accepted::DB_TABLE;
			$query .= " WHERE project_id = ".$projectID;
			$query .= " AND status != ".Accepted::STATUS_RELEASED;
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

	/* instance methods */
	
	public function getReadMessages()
	{
		return (Message::getReceivedMessagesByUserID($this->id,null,false));
	}
	
	public function getUnreadMessages()
	{
		return (Message::getReceivedMessagesByUserID($this->id,null,true));
	}
	
	public function getNumUnreadMessages()
	{
		$unreadMessages = self::getUnreadMessages();
		return (count($unreadMessages));
	}
	
	public function toString()
	{
		return ($this->userName);
	}
	
	// --- only getters and setters below here --- //
	

	public function getID()
	{
		return ($this->id);
	}
	
	public function setID($newID)
	{
		$this->modified = true;
		$this->id = $newID;
	}
	
	public function getUsername()
	{
		return ($this->username);
	}
	
	public function setUsername($newUsername)
	{
		$this->modified = true;
		$this->username = $newUsername;
	}
	
	public function getPassword()
	{
		return ($this->password);
	}
	
	public function setPassword($newPassword)
	{
		$this->modified = true;
		$this->password = $newPassword;
	}
	
	public function getEmail()
	{
		return ($this->email);
	}
	
	public function setEmail($newEmail)
	{
		$this->modified = true;	
		$this->email = $newEmail;
	}
	
	public function getName()
	{
		return ($this->name);
	}
	
	public function setName($newName)
	{
		$this->modified = true;
		$this->name = $newName;
	}
	
	public function getSex()
	{
		return ($this->sex);
	}
	
	public function setSex($newSex)
	{
		$this->modified = true;	
		$this->sex = $newSex;
	}
	
	public function getLocation()
	{
		return ($this->location);
	}
	
	public function setLocation($newLocation)
	{
		$this->modified = true;	
		$this->location = $newLocation;
	}
	
	public function getDOB()
	{
		return ($this->dob);
	}
	
	public function setDOB($newDOB)
	{
		$this->modified = true;	
		$this->dob = $newDOB;
	}
	
	public function getBiography()
	{
		return ($this->biography);
	}
	
	public function setBiography($newBiography)
	{
		$this->modified = true;
		$this->biography = $newBiography;
	}
	
	public function setPictureSmall($newPictureSmall)
	{
		$this->modified = true;
		$this->pictureSmall = $newPictureSmall;
	}
	
	public function getPictureSmall()
	{
		return ($this->pictureSmall);
	}

	public function setPictureLarge($newPictureLarge)
	{
		$this->modified = true;
		$this->pictureLarge = $newPictureLarge;
	}
	
	public function getPictureLarge()
	{
		return ($this->pictureLarge);
	}
	
	public function setPicture($newPicture)
	{
		$this->modified = true;
		$this->picture = $newPicture;
	}
	
	public function getPicture()
	{
		return ($this->picture);
	}
	
	public function getThemeID() {
		return ($this->themeID);
	}
	
	public function setThemeID($newThemeID) {
		$this->themeID = $newThemeID;
		$this->modified = true;
	}
	
	public function getNotifyCommentTaskLeading() {
		return ($this->notifyCommentTaskLeading);
	}
	
	public function setNotifyCommentTaskLeading($newNotifyCommentTaskLeading) {
		$this->notifyCommentTaskLeading = $newNotifyCommentTaskLeading;
		$this->modified = true;
	}
	
	public function getNotifyEditTaskAccepted() {
		return ($this->notifyEditTaskAccepted);
	}
	
	public function setNotifyEditTaskAccepted($newNotifyEditTaskAccepted) {
		$this->notifyEditTaskAccepted = $newNotifyEditTaskAccepted;
		$this->modified = true;
	}

	public function getNotifyCommentTaskAccepted() {
		return ($this->notifyCommentTaskAccepted);
	}
	
	public function setNotifyCommentTaskAccepted($newNotifyCommentTaskAccepted) {
		$this->notifyCommentTaskAccepted = $newNotifyCommentTaskAccepted;
		$this->modified = true;
	}

	public function getNotifyCommentTaskUpdate() {
		return ($this->notifyCommentTaskUpdate);
	}
	
	public function setNotifyCommentTaskUpdate($newNotifyCommentTaskUpdate) {
		$this->notifyCommentTaskUpdate = $newNotifyCommentTaskUpdate;
		$this->modified = true;
	}

	public function getNotifyInviteProject() {
		return ($this->notifyInviteProject);
	}
	
	public function setNotifyInviteProject($newNotifyInviteProject) {
		$this->notifyInviteProject = $newNotifyInviteProject;
		$this->modified = true;
	}

	public function getNotifyTrustProject() {
		return ($this->notifyTrustProject);
	}
	
	public function setNotifyTrustProject($newNotifyTrustProject) {
		$this->notifyTrustProject = $newNotifyTrustProject;
		$this->modified = true;
	}
	
	public function getNotifyBannedProject() {
		return ($this->notifyBannedProject);
	}
	
	public function setNotifyBannedProject($newNotifyBannedProject) {
		$this->notifyBannedProject = $newNotifyBannedProject;
		$this->modified = true;
	}
	
	public function getNotifyDiscussionStarted() {
		return ($this->notifyDiscussionStarted);
	}
	
	public function setNotifyDiscussionStarted($newNotifyDiscussionStarted) {
		$this->notifyDiscussionStarted = $newNotifyDiscussionStarted;
		$this->modified = true;
	}		

	public function getNotifyDiscussionReply() {
		return ($this->notifyDiscussionReply);
	}
	
	public function setNotifyDiscussionReply($newNotifyDiscussionReply) {
		$this->notifyDiscussionReply = $newNotifyDiscussionReply;
		$this->modified = true;
	}	
	
	public function getNotifyMakeTaskLeader() {
		return ($this->notifyMakeTaskLeader);
	}
	
	public function setNotifyMakeTaskLeader($newNotifyMakeTaskLeader) {
		$this->notifyMakeTaskLeader = $newNotifyMakeTaskLeader;
		$this->modified = true;
	}
	
	public function getNotifyReceiveMessage() {
		return ($this->notifyReceiveMessage);
	}
	
	public function setNotifyReceiveMessage($newNotifyReceiveMessage) {
		$this->notifyReceiveMessage = $newNotifyReceiveMessage;
		$this->modified = true;
	}
	
	public function getNotifyMassEmail() {
		return ($this->notifyMassEmail);
	}
	
	public function setNotifyMassEmail($newNotifyMassEmail) {
		$this->notifyMassEmail = $newNotifyMassEmail;
		$this->modified = true;
	}
	
	public function getAdmin() {
		return ($this->admin);
	}
	
	public function setAdmin($newAdmin) {
		$this->admin = $newAdmin;
		$this->modified = true;
	}
	
	public function getDateCreated()
	{
		return ($this->dateCreated);
	}
	
	public function setDateCreated($newDateCreated)
	{
		$this->modified = true;	
		$this->dateCreated = $newDateCreated;
	}

	public function getLastLogin()
	{
		return ($this->lastLogin);
	}
	
	public function setLastLogin($newLastLogin)
	{
		$this->modified = true;	
		$this->lastLogin = $newLastLogin;
	}
	
	public function getSecondLastLogin()
	{
		return ($this->secondLastLogin);
	}
	
	public function setSecondLastLogin($newSecondLastLogin)
	{
		$this->modified = true;	
		$this->secondLastLogin = $newSecondLastLogin;
	}
}