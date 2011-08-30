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
			self::oneCrumb($title, Url::task($taskID)),
			self::tasks($projectID)
		);		
	}
	
	public static function taskNew($projectID=null)
	{
		if($projectID == null) return null;
		$title = 'New Task';
		return array_merge(
			self::oneCrumb($title, Url::taskNew($projectID)),
			self::tasks($projectID)
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
			self::oneCrumb($title, Url::update($updateID)),
			self::task($accepted->getTaskID())
		);
	}
	
	public static function updateNew($taskID=null) {
		if($taskID == null) return null;
		$title = 'New Update';
		return array_merge(
			self::oneCrumb($title, Url::updateNew($taskID)),
			self::task($taskID)
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
			self::oneCrumb($title, Url::discussion($discussionID)),
			self::discussions($projectID)
		);
	}
	
	public static function discussionNew($projectID=null) {
		if($projectID == null) return null;
		$title = 'New Discussion';
		return array_merge(
			self::oneCrumb($title, Url::discussionNew($projectID)),
			self::discussions($projectID)
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