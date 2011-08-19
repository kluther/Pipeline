<?php

class Section
{
	const ACTIVITY = 1;
	const DETAILS = 2;
	const TASKS = 3;
	const DISCUSSIONS = 4;
	const PEOPLE = 5;

	public static function getURL($sectionID=null, $projectID=null)
	{
		if( ($sectionID == null) || ($projectID == null) ) return null;
		switch($sectionID)
		{
			case self::ACTIVITY:
				$url = Url::activity($projectID);
				break;
			case self::DETAILS:
				$url = Url::details($projectID);
				break;
			case self::TASKS:
				$url = Url::tasks($projectID);
				break;
			case self::DISCUSSIONS:
				$url = Url::discussions($projectID);
				break;
			case self::PEOPLE:
				$url = Url::people($projectID);
				break;
		}
		return $url;
	}
	
	public static function getName($sectionID=null, $projectID=null)
	{
		if( ($sectionID == null) || ($projectID == null) ) return null;
		switch($sectionID)
		{
			case self::ACTIVITY:
				$name = "Activity";
				break;
			case self::DETAILS:
				$name = "Details";
				break;
			case self::TASKS:
				$name = "Tasks";
				break;
			case self::DISCUSSIONS:
				$name = "Discussions";
				break;
			case self::PEOPLE:
				$name = "People";
				break;
		}
		return $name;
	}	
}

