<?php

class Chat extends DbObject
{
	protected $id;
	protected $sender;
	protected $recipient;
	protected $message;
	protected $sent;
	
	const DB_TABLE = 'chat';

	public function __construct($args = array())

	{
		$defaultArgs = array(
			'id' => null,
			'sender' => 0,
			'recipient' => 0,
			'message' => '',
			'sent' => null
		);

		$args += $defaultArgs;
		$this->id = $args['id'];
		$this->sender = $args['sender'];
		$this->recipient = $args['recipient'];
		$this->message = $args['message'];
		$this->sent = $args['sent'];
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
		// map database fields to class properties; omit id
		$db_properties = array(
			'sender' => $this->sender,
			'recipient' => $this->recipient,
			'message' => $this->message,
			'sent' => $this->sent			
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
        }
		
        /*getChats() - returns an array of chat messages for a specified recipient
         * 
         * Input:
         *      $to(string)                    - recipient id (string) of chat message [Required]
         *      $fromRecord(int)               - return records with id greater than specified id [Optional] 
         *      $startingDate(NOT IMPLEMENTED) - return records that have a sent date greater or equal to the starting date
         *      $endingDate(NOT IMPLEMENTED)   - return records that have an end date less than or eqal to the ending date
         * Output:
         *      Returns an array of chat messages for a specified recipient
         * Side Effect:
         *      None          
         */
        public static function getChats($to=null,$fromRecord=null,$startingDate=null,$endingDate=null) {
                if($to === null) return null;
                
                $chats = array();
                $fromRecord = (empty($fromRecord) OR !is_numeric($fromRecord)) ? 0 : $fromRecord; 
                
                $query = " SELECT id FROM ".self::DB_TABLE;
		$query .= " WHERE (recipient = '".$to."'";
                $query .= " AND id > ".$fromRecord.")";
		$query .= " ORDER BY id ASC";
                
                $db = Db::instance();
		$result = $db->lookup($query);
		
		if (!mysql_num_rows($result)) {return null;}
                while ($row = mysql_fetch_assoc($result)){
			$chats[$row['id']] = get_object_vars(self::load($row['id']));
                }
		return $chats;
        }
        
        /*updateUserLocation() - Sets a timestamp and chatroom id in a user record to specify that a user is currently in the chatroom
         *     Note: Since only one chatroom can be stored to the user record at a time, if a user is in multiple chatrooms at the same time, the
         *            user record will look as though they are jumping back and forth between rooms.
         * 
         * Input:
         *      $userId(int)                   - user id to update location for [Required]
         *      $projectId (int)               - id of chatroom (which happens to coincide with project id) [Optional] 
         * Output:
         *      Returns null if userId or projectId is not specified.
         * Side Effect:
         *      Sets timestamp in user record and records chatroom that user was in          
         */
        public static function updateUserLocation($userId=null,$projectId=null)
	{
                if(($userId === null) || ($projectId === null)) return null;
                
		$db = Db::instance();
		$query = "UPDATE user"." SET last_heartbeat = '".date("Y-m-d H:i:s")."', latest_chatroom = '".$projectId."' WHERE id= '".$userId."'";
		$result = $db->lookup($query);
		return $result;
	}
        
        /*getOnlineUsers() - Finds all of the users who have recently performed a heartbeat in a chatroom
         * 
         * Input:
         *      $recipient(string)               - id of chatroom (which happens to coincide with project id) [Required]
         *      $userIds(string)                 - comma-delimited string of integers that represent the user ids you want check whether they are online
         *                                          Since Pipeline is project based, we will only ever want the users tied to a specific project
         *                                          active in a specific chat room [Required]
         *      $interval(int)                   - number of seconds that you want to check whether a heartbeat was received from a user [Optional] 
         * Output:
         *      Returns an array of usernames who have pinged a specific chatroom within the interval specified (15 seconds default)
         * Side Effect:
         *      None          
         */
        public static function getOnlineUsers($recipient=null,$userIds=null,$interval=15)
        {
            if(($recipient === null) || ($userIds === null)) return null;
                
                $users = array();
                                
                $query = " SELECT username FROM user WHERE id IN (".$userIds.")";
		$query .= " AND latest_chatroom = '".$recipient."'";
                $query .= " AND last_heartbeat BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL ".$interval." SECOND))";
                $query .= " AND timestamp(NOW())";
		                
                $db = Db::instance();
		$result = $db->lookup($query);
                
                if (!mysql_num_rows($result)) {return null;}
                while ($row = mysql_fetch_assoc($result)){
                    array_push($users, $row['username']);
                }
		return $users;
        }
       
// getters and setters

	public function getID()
	{
		return ($this->id);
	}
	
	public function setID($newID)
	{
		$this->id = $newID;
		$this->modified = true;
	}
	
	public function getSenderID()
	{
		return ($this->sender);
	}
	
	public function getSender()
	{
		return (User::load($this->sender));
	}
	
	public function setSenderID($newSenderID)
	{
		$this->sender = $newSenderID;
		$this->modified = true;
	}
	
	public function getRecipientID()
	{
		return ($this->recipient);
	}
	
	public function getRecipient()
	{
		return (User::load($this->recipient));
	}
	
	public function setRecipientID($newRecipientID)
	{
		$this->recipient = $newRecipientID;
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
	

}