<?php

class Theme
{
	protected $id;
	protected $name;
	protected $pipelineStylesheet;
	protected $jqueryuiStylesheet;

	const DB_TABLE = "theme";
	
	public function __construct($args=array())
	{
		$defaultArgs = array(
			'id' => null,
			'name' => '',
			'pipeline_stylesheet' => '',
			'jqueryui_stylesheet' => ''
		);	
		
		$args += $defaultArgs;
		
		$this->id = $args['id'];
		$this->name = $args['name'];
		$this->pipelineStylesheet = $args['pipeline_stylesheet'];
		$this->jqueryuiStylesheet = $args['jqueryui_stylesheet'];
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
			'name' => $this->name,
			'pipeline_stylesheet' => $this->pipelineStylesheet,
			'jqueryui_stylesheet' => $this->jqueryuiStylesheet
		);		
		$db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
	}	
	
// static methods //

	public static function getThemes() {
		$query = "SELECT id FROM ".self::DB_TABLE;
		$query .= " ORDER BY name ASC";
		
		$db = Db::instance();
		$result = $db->lookup($query);
		if(!mysql_num_rows($result)) return null;
		
		$themes = array();
		while($row = mysql_fetch_assoc($result))
			$themes[$row['id']] = self::load($row['id']);
		return $themes;	
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

	public function getName() {
		return ($this->name);
	}
	
	public function setName($newName) {
		$this->name = $newName;
		$this->modified = true;
	}
	
	public function getPipelineStylesheet() {
		return ($this->pipelineStylesheet);
	}
	
	public function setPipelineStylesheet($newPipelineStylesheet) {
		$this->pipelineStylesheet = $newPipelineStylesheet;
		$this->modified = true;
	}
	
	public function getJqueryuiStylesheet() {
		return ($this->jqueryuiStylesheet);
	}
	
	public function setJqueryuiStylesheet($newJqueryuiStylesheet) {
		$this->jqueryuiStylesheet = $newJqueryuiStylesheet;
		$this->modified = true;
	}
	
}