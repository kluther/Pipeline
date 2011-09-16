<?php
include_once SYSTEM_PATH.'/lib/human_time_diff.php';

function formatFileSize($size) {
	// some code from http://www.php.net/manual/en/function.filesize.php#100097
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	$decPlaces = ($i>=2) ? 1 : 0;
    return round($size, $decPlaces).$units[$i];
}

function formatUpdate($update) {
	return (formatParagraphs($update));
}

function formatComment($comment) {
	return (formatParagraphs($comment));
}

function formatDiscussionReply($reply) {
	return (formatParagraphs($reply));
}

function formatTaskDescription($description) {
	return (formatParagraphs($description));
}

function formatPitch($pitch) {
	return (formatParagraphs($pitch));
}

/* generic function for formatting paragraphs of HTML text */
function formatParagraphs($paragraphs) {
	$formatted = html_entity_decode($paragraphs, ENT_QUOTES, 'ISO-8859-15');
	$formatted = str_replace("\n","<br />",$formatted);
	return $formatted;
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
	$formatted = '<a href="'.Url::project($projectID).'">'.html_entity_decode($project->getTitle(), ENT_QUOTES, 'ISO-8859-15').'</a>';
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

function formatSpecs($specs) {
	if(empty($specs)) return null;
	$specs = html_entity_decode($specs, ENT_QUOTES, 'ISO-8859-15');
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
		} else {
			$formattedSpecs[$i] = $lines[$i];
		}
	}
	return $formattedSpecs;
}

function formatRules($rules) {
	if(empty($rules)) return null;
	$rules = html_entity_decode($rules, ENT_QUOTES, 'ISO-8859-15');
	$lines = explode("\n",$rules); // line feeds

	$formattedRules = array();
	for($i=0; $i<count($lines); $i++) {
		if(substr($lines[$i],0,1) == '+')
			$formattedRules[$i] = '<span class="good">'.$lines[$i].'</span>';
		elseif(substr($lines[$i],0,1) == '-')
			$formattedRules[$i] = '<span class="bad">'.$lines[$i].'</span>';
		else
			$formattedRules[$i] = '<span>'.$lines[$i].'</span>';
	}
	return $formattedRules;
}