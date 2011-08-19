<?php

class Upload extends DbObject
{
	protected $id;
	protected $kalturaID;
	protected $creatorID;	
	protected $projectID;
	protected $itemType;
	protected $itemID;
	protected $originalName;
	protected $storedName;
	protected $mime;
	protected $size;
	protected $token;
	protected $deleted;
	protected $dateCreated;

	const DB_TABLE = 'upload';
	
	const TYPE_TASK = 'task';
	const TYPE_UPDATE = 'update';
	const TYPE_DISCUSSION = 'discussion';
	
	const THUMB_MAX_WIDTH = 150;
	const THUMB_MAX_HEIGHT = 150;
	
	public function __construct($args = array())
	{
		$defaultArgs = array
		(
			'id' => null,
			'kaltura_id' => '',
			'creator_id' => 0,
			'project_id' => null,
			'item_type' => null,
			'item_id' => null,
			'original_name' => '',
			'stored_name' => '',
			'mime' => '',
			'size' => 0,
			'token' => '',
			'deleted' => 0,
			'date_created' => null
		);
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->kalturaID = $args['kaltura_id'];
		$this->creatorID = $args['creator_id'];		
		$this->projectID = $args['project_id'];
		$this->itemType = $args['item_type'];
		$this->itemID = $args['item_id'];
		$this->originalName = $args['original_name'];
		$this->storedName = $args['stored_name'];
		$this->mime = $args['mime'];
		$this->size = $args['size'];
		$this->token = $args['token'];
		$this->deleted = $args['deleted'];
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
		// map database fields to class properties; omit id and dateTimeCreated
		$db_properties = array(
			'kaltura_id' => $this->kalturaID,
			'creator_id' => $this->creatorID,		
			'project_id' => $this->projectID,
			'item_type' => $this->itemType,
			'item_id' => $this->itemID,
			'original_name' => $this->originalName,
			'stored_name' => $this->storedName,
			'mime' => $this->mime,
			'size' => $this->size,
			'token' => $this->token,
			'deleted' => $this->deleted
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}		

	public function delete() {
		$query = "UPDATE ".self::DB_TABLE." SET deleted=1 WHERE id=".$this->id;
		$db = Db::instance();
		$db->execute($query);
	}

	
	// public function restore()
	// {
		// $db = Db::instance();
		// $newStoredName = substr($this->storedName,0,-4); // cut off ".del"
		
		// $query = "UPDATE ".self::DB_TABLE." SET deleted=0, stored_name='" .$newStoredName. "' WHERE id=".$this->id;
		// $db->execute($query);
		
		// $file_location = SYSTEM_PATH . Url::FILE_DIR_ABS . $newStoredName;
		// rename($file_location.'.del', $file_location);
	// }
	
	// public static function deleteByID($id=null)
	// {
		// if ($id == null) {return null;}
		
		// $file = self::load($id);
		// $result = $file->delete();
		// return $result;
	// }
	
	// public function isDeleted()
	// {
		// // if the file itself is deleted, we can stop here
		// if($this->deleted)
			// return true;
		// elseif($this->nodeID != null)
		// {
			// // it's submission file
			// $version = Version::load($this->nodeID);
			// // if parent version is deleted, so is the file
			// if($version->isDeleted())
				// return true;
			// else
				// return false;
		// }
		// else
		// {
			// // it's a project file
			// return false;
		// }
	// }	
	
	/* Static Methods */

	// /* can we generate a preview version for this upload? */
	// public static function hasPreview($mime) {
		// $hasPreview = false;
		// switch($mime) {
			// // flash or video
			// case 'application/x-shockwave-flash': // .swf
			// case 'video/x-flv': // .flv
			// case 'video/mpeg':
			// case 'video/quicktime':
			// case 'video/x-msvideo': // .avi
			// // audio
			// case 'audio/mpeg': // .mp3
				// $hasPreview = true;
		// }
		// return ($hasPreview);
	// }
	
	// /* can we generate a thumbnail for this upload? */
	// public static function hasThumb($mime) {
		// $hasThumb = false;
		// switch($mime) {
			// // images
			// case 'image/png':
			// case 'image/jpeg':
			// case 'image/pjpeg':
			// case 'image/gif':
			// // flash or video
			// case 'application/x-shockwave-flash': // .swf
			// case 'video/x-flv': // .flv
			// case 'video/mpeg':
			// case 'video/quicktime':
			// case 'video/x-msvideo': // .avi
				// $hasThumb = true;
		// }
		// return ($hasThumb);
	// }
	
	/* is this mime type allowed to be uploaded? */
	public static function isAllowedMime($mime) {
		$isAllowed = false;
		switch($mime) {
			// images
			case 'image/png': 
			case 'image/jpeg':
			case 'image/pjpeg': 
			case 'image/gif': 
			// flash or video
			case 'application/x-shockwave-flash': 
			case 'video/x-flv': 
			case 'video/mpeg': 
			case 'video/quicktime': 
			case 'video/x-msvideo': 
			// audio
			case 'audio/mpeg': 
			// misc
			case 'application/octet-stream':
				$isAllowed = true;
		}
		return ($isAllowed);
	}
	
	public static function isAllowedExtension($ext) {
		$isAllowed = false;
		switch($ext) {
			// images
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			// flash
			case 'swf':
			case 'fla':
			case 'flv':
			// video
			case 'mov':
			case 'mpg':
			case 'mpeg':
			case 'avi':
			// audio
			case 'mp3':
				$isAllowed = true;
		}
		return ($isAllowed);
	}
	
	public static function getByTaskID($taskID=null, $deleted=null) {
		return (self::getByItemID(self::TYPE_TASK, $taskID, $deleted));		
	}
	
	public static function getByUpdateID($updateID=null, $deleted=null) {
		return (self::getByItemID(self::TYPE_UPDATE, $updateID, $deleted));		
	}

	public static function getByDiscussionID($discussionID=null, $deleted=null) {
		return (self::getByItemID(self::TYPE_DISCUSSION, $discussionID, $deleted));		
	}
	
	public static function getByItemID($itemType=null, $itemID=null, $deleted=null) {
		if( ($itemType == null) || ($itemID == null) ) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE item_type = '".$itemType."'";
		$query .= " AND item_id = ".$itemID;
		if($deleted === true)
			$query .= " AND deleted=1";
		elseif($deleted === false)
			$query .= " AND deleted=0";
		$query .= " ORDER BY date_created DESC";
		//echo $query;
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$uploads = array();
		while($row = mysql_fetch_assoc($result))
			$uploads[$row['id']] = self::load($row['id']);
		return $uploads;			
	}	
	
	public static function getByToken($token=null) {
		if($token == null) return null;
		
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE token = '".$token."'";
		$query .= " ORDER BY date_created DESC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return array();

		$uploads = array();
		while($row = mysql_fetch_assoc($result))
			$uploads[$row['id']] = self::load($row['id']);
		return $uploads;		
	}
	
	public static function generateToken() {
		return (sha1(microtime(true).mt_rand(10000,90000)));
	}
	
	/* attach uploads to an item based on matched token */
	/* returns null if missing arguments */
	/* returns false if no matches */
	/* otherwise, returns array of matched uploads */
	public static function attachToItem($token=null, $itemType=null, $itemID=null, $projectID=null) {
		if( ($token == null) || ($itemType == null) || ($itemID == null) ) {
			return null;
		}
		
		// get unattached uploads with this token
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE token='".$token."'";
		$query .= " AND item_type IS NULL";

		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result))
			return false;

		$uploads = array();
		while($row = mysql_fetch_assoc($result)) {
			$upload = self::load($row['id']);
			// associate item data with this upload
			$upload->setItemType($itemType);
			$upload->setItemID($itemID);
			if($projectID != null)
				$upload->setProjectID($projectID);
			$upload->save();
			$uploads[$row['id']] = $upload;
		}
		return $uploads;
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
	
	public function getKalturaID() {
		return ($this->kalturaID);
	}
	
	public function setKalturaID($newKalturaID) {
		$this->kalturaID = $newKalturaID;
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
		$this->creatorID = newCreatorID;
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
	
	public function getOriginalName()
	{
		return ($this->originalName);
	}
	
	public function setOriginalName($newOriginalName)
	{
		$this->originalName = $newOriginalName;
		$this->modified = true;
	}
	
	public function getStoredName()
	{
		return ($this->storedName);
	}
	
	public function setStoredName($newStoredName)
	{
		$this->storedName = $newStoredName;
		$this->modified = true;
	}
	
	public function getMime() {
		return ($this->mime);
	}
	
	public function setMime($newMime) {
		$this->mime = $newMime;
		$this->modified = true;
	}
	
	public function getItemType() {
		return ($this->itemType);
	}
	
	public function setItemType($newItemType) {
		$this->itemType = $newItemType;
		$this->modified = true;
	}
	
	public function getItemID() {
		return ($this->itemID);
	}
	
	public function setItemID($newItemID) {
		$this->itemID = $newItemID;
		$this->modified = true;
	}
	
	public function getSize() {
		return ($this->size);
	}
	
	public function setSize($newSize) {
		$this->size = $newSize;
		$this->modified = true;
	}
	
	public function getToken() {
		return ($this->token);
	}
	
	public function setToken($newToken) {
		$this->token = $newToken;
		$this->modified = true;
	}

	// public function getFileExtension()
	// {
		// $dot = strrpos($this->origFileName, '.');
		
		// if(!$dot)
			// return null; // filename has no dot; improperly formatted
		// else
		// {
			// $extension = substr($this->origFileName, $dot+1);
			// return (strtolower($extension));
		// }
	// }
	
	
	/* Return file size in kilobytes, if known */
	// public function getFileSize()
	// {
		// $absPath = UPLOAD_PATH.'/resource/'.$this->storedName;
		// $fileSize = filesize($absPath);
		// if($fileSize === false)
			// return 'unknown';
		// else
			// return(ceil($fileSize/1024));
	// }
	
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
