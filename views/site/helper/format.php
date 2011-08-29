<?php
include_once SYSTEM_PATH.'/lib/human_time_diff.php';

function formatFileSize($size) {
	// some code from http://www.php.net/manual/en/function.filesize.php#100097
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	$decPlaces = ($i>=2) ? 1 : 0;
    return round($size, $decPlaces).$units[$i];
}

function formatUpdate($update)
{
	$formattedUpdate = html_entity_decode($update);
	$formattedUpdate = str_replace("\n","<br />",$formattedUpdate);
	return $formattedUpdate;
}

function formatTaskDescription($description)
{
	$formattedDescription = html_entity_decode($description);
	$formattedDescription = str_replace("\n","<br />",$formattedDescription);
	return $formattedDescription;
}

function formatUserPicture($userID=null, $size='large') {
	if($userID == null) return null;
	$user = User::load($userID);
	if($size == 'large') {
		return ('<a class="picture" href="'.Url::user($user->getID()).'" title="'.$user->getUsername().'"><img src="'.Url::userPictureLarge($user->getID()).'" /></a>');
	} elseif($size == 'small') {
		return ('<a class="picture small" href="'.Url::user($user->getID()).'" title="'.$user->getUsername().'"><img src="'.Url::userPictureSmall($user->getID()).'" /></a>');
	} else {
		return '';
	}
}

function formatUserLink($userID=null)
{
	if($userID == null) return null;
	$user = User::load($userID);
	$formatted = '<a href="'.Url::user($userID).'">'.$user->getUsername().'</a>';
	return $formatted;
}

function formatProjectStatus($status=null) {
	if($status === null) return null;
	if($status == Project::STATUS_PRE_PRODUCTION) {
		return "pre-production";
	} elseif($status == Project::STATUS_IN_PRODUCTION) {
		return "in production";
	} elseif($status == Project::STATUS_POST_PRODUCTION) {
		return "post-production";
	} elseif($status == Project::STATUS_COMPLETED) {
		return "finished";
	} else {
		return "canceled";		
	}
}

function formatProjectLink($projectID=null)
{
	if($projectID == null) return null;
	$project = Project::load($projectID);
	$formatted = '<a href="'.Url::project($projectID).'">'.$project->getTitle().'</a>';
	return $formatted;
}

function formatSectionLink($sectionID=null, $projectID=null)
{
	if( ($sectionID == null) || ($projectID == null) ) return null;
	switch($sectionID)
	{
		case ACTIVITY_ID:
			$url = Url::activity($projectID);
			$name = "Activity";
			break;
		case BASICS_ID:
			$url = Url::details($projectID);
			$name = "Basics";
			break;
		case TASKS_ID:
			$url = Url::tasks($projectID);
			$name = "Tasks";
			break;
		case DISCUSSIONS_ID:
			$url = Url::discussions($projectID);
			$name = "Discussions";
			break;
		case PEOPLE_ID:
			$url = Url::people($projectID);
			$name = "People";
			break;
	}
	$formatted = '<a href="'.$url.'">'.$name.'</a>';
	return $formatted;
}

function formatTimestamp($t)
{
	$t = strtotime($t);
	return strftime('%a, %b %d, %Y %I:%M %p', $t);
}

function formatTimeDiff($from, $to='')
{
	if($to=='') $to=date("r");
	$from = strtotime($from);
	$to = strtotime($to);	
	$time = human_time_diff($from,$to);
	return ($time);
}

function formatTimeTag($t, $tagName = 'span')
{
	$title = formatTimestamp($t);
	$text = formatTimeDiff($t);
	
	return "<{$tagName} class=\"datetime\" title=\"{$title}\">{$text}</{$tagName}>";
}

function formatPitch($pitch)
{
	$formattedPitch = html_entity_decode($pitch);
	$formattedPitch = str_replace("\n","<br />",$formattedPitch);
	return $formattedPitch;
}

function formatSpecs($specs)
{
	$specs = html_entity_decode($specs);
	$lines = explode("\n",$specs); // line feeds
	$formattedSpecs = array();
	for($i=0; $i<count($lines); $i++)
	{
		$spec = explode(":",$lines[$i]);
		if(count($spec)>1)
		{
			$key = "<strong>".$spec[0]."</strong>: ";
			$value = $spec[1];
			$formattedSpecs[$i] = $key.$value;
		}
	}
	return $formattedSpecs;
}

function formatRules($rules)
{
	$rules = html_entity_decode($rules);
	$lines = explode("\n",$rules); // line feeds
	$formattedRules = array();
	for($i=0; $i<count($lines); $i++)
	{
		if(substr($lines[$i],0,1) == '+')
			$formattedRules[$i] = '<span class="good">'.$lines[$i].'</span>';
		elseif(substr($lines[$i],0,1) == '-')
			$formattedRules[$i] = '<span class="bad">'.$lines[$i].'</span>';
		else
			$formattedRules[$i] = '<span>'.$lines[$i].'</span>';
	}
	return $formattedRules;
}