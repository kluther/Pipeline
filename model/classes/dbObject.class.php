<?php

class DbObject
{
	protected $modified = false;
	
	public function getModified()
	{
		return $this->modified;
	}
	
	public function setModified($modified=false)
	{
		$this->modified = $modified;
	}
}