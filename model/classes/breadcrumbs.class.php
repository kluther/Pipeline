<?php

class Breadcrumbs
{
	
	private static function oneCrumb($title, $url)
	{
		return array(array($title, $url));
	}
	
	// public static function home()
	// {
		// $title = "Home";
		// return self::oneCrumb($title, Url::home());
	// }
	
	public static function activity($projectID=null)
	{
		if($projectID == null) return null;
		return (self::oneCrumb("Activity", Url::activity($projectID)));
	}

	public static function details($projectID=null)
	{
		if($projectID == null) return null;
		return (self::oneCrumb("Details", Url::details($projectID)));
	}
	
	public static function tasks($projectID=null)
	{
		if($projectID == null) return null;
		return (self::oneCrumb("Tasks", Url::tasks($projectID)));
	}
	
	public static function task($taskID=null)
	{
		if($taskID == null) return null;
	
		$task = Task::load($taskID);
		$title = $task->getTitle();
		$projectID = $task->getProjectID();
		return array_merge(
			self::tasks($projectID),
			self::oneCrumb($title, Url::task($taskID))
		);		
	}
	
	public static function taskNew($projectID=null)
	{
		if($projectID == null) return null;
		$title = 'New Task';
		return array_merge(
			self::tasks($projectID),
			self::oneCrumb($title, Url::taskNew($projectID))
		);		
	}	
	
	public static function update($updateID=null)
	{
		if($updateID == null) return null;
		$update = Update::load($updateID);
	//	$user = User::load($update->getCreatorID());
	//	$username = $user->getUsername();
		$title = $update->getTitle();
		$accepted = Accepted::load($update->getAcceptedID());
		return array_merge(
			self::task($accepted->getTaskID()),
			self::oneCrumb($title, Url::update($updateID))
		);
	}
	
	public static function updateNew($taskID=null) {
		if($taskID == null) return null;
		$title = 'New Contribution';
		return array_merge(
			self::task($taskID),
			self::oneCrumb($title, Url::updateNew($taskID))
		);
	}

	public static function discussions($projectID=null)
	{
		if($projectID == null) return null;
		return (self::oneCrumb("Discussions", Url::discussions($projectID)));
	}

	public static function discussion($discussionID=null)
	{
		$discussion = Discussion::load($discussionID);
		$title = $discussion->getTitle();
		$projectID = $discussion->getProjectID();
		return array_merge(
			self::discussions($projectID),
			self::oneCrumb($title, Url::discussion($discussionID))
		);
	}
	
	public static function discussionNew($projectID=null) {
		if($projectID == null) return null;
		$title = 'New Discussion';
		return array_merge(
			self::discussions($projectID),
			self::oneCrumb($title, Url::discussionNew($projectID))
		);
	}
	
	public static function people($projectID=null)
	{
		if($projectID == null) return null;
		return (self::oneCrumb("People", Url::people($projectID)));
	}
	
	// public static function banned($projectID=null)
	// {
		// if($projectID == null) return null;
		// return array_merge(
			// self::oneCrumb("Banned", Url::banned($projectID)),
			// self::people($projectID)
		// );
	// }
}