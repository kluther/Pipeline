<?php

class EventType
{
	protected $id;
	protected $description;
	protected $group;
	protected $cssClass;
	protected $diffable;
	protected $hidden;
	protected $contribution;

	const DB_TABLE = "event_type";
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'description' => null,
			'group' => 0,
			'css_class' => '',
			'diffable' => 0,
			'hidden' => 0,
			'contribution' => 0
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->description = $args['description'];
		$this->group = $args['group'];
		$this->cssClass = $args['css_class'];
		$this->diffable = $args['diffable'];
		$this->hidden = $args['hidden'];
		$this->contribution = $args['contribution'];
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
			'description' => $this->description,
			'group' => $this->group,
			'css_class' => $this->cssClass,
			'diffable' => $this->diffable,
			'hidden' => $this->hidden,
			'contribution' => $this->contribution
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
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

	public function getDescription()
	{
		return ($this->description);
	}
	
	public function setDescription($newDescription)
	{
		$this->description = $newDescription;
		$this->modified = true;
	}
	
	public function getGroup()
	{
		return ($this->group);
	}
	
	public function setGroup($newGroup)
	{
		$this->group = $newGroup;
		$this->modified = true;
	}
	
	public function getCssClass()
	{
		return ($this->cssClass);
	}
	
	public function setCssClass($newCssClass)
	{
		$this->cssClass = $newCssClass;
		$this->modified = true;
	}
	
	public function getDiffable()
	{
		return ($this->diffable);
	}
	
	public function setDiffable($newDiffable)
	{
		$this->diffable = $newDiffable;
		$this->modified = true;
	}
	
	public function getHidden()
	{
		return ($this->hidden);
	}
	
	public function setHidden($newHidden)
	{
		$this->hidden = $newHidden;
		$this->modified = true;
	}
	
	public function getContribution() {
		return ($this->contribution);
	}
	
	public function setContribution($newContribution) {
		$this->contribution = $newContribution;
		$this->modified = true;
	}
	
}