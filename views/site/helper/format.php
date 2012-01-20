<?php
include_once SYSTEM_PATH.'/lib/human_time_diff.php';
include_once SYSTEM_PATH.'/lib/finediff.php';

function formatFileSize($size) {
	// some code from http://www.php.net/manual/en/function.filesize.php#100097
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	$decPlaces = ($i>=2) ? 1 : 0;
    return round($size, $decPlaces).$units[$i];
}

function formatInboxMessage($message) {
	return (formatParagraphs($message));
}

function formatInvitationMessage($message) {
	return (formatParagraphs($message));
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

/* note: only used for upload file names right now */
function truncateFileName($fileName, $maxLength=30) {
	if(strlen($fileName)>$maxLength) {
		$a = substr($fileName,0,$maxLength-10);
		$b = substr($fileName,-10);
		$formattedFileName = $a.'&hellip;'.$b;
	} else {
		$formattedFileName = $fileName;
	}
	return ($formattedFileName);
	//return '<a href="'.$fileName.'">'.$formattedFileName.'</a>';
}

function truncateURL($matches) {
	$space = $matches[1];
	$url = $matches[2];
	$space2 = $matches[3];
	$maxLength = 50;
	if(strlen($url)>$maxLength) {
		$a = substr($url,0,$maxLength-10);
		$b = substr($url,-10);
		$formattedUrl = $a.htmlentities('&hellip;').$b;
	} else {
		$formattedUrl = $url;
	}
	return $space.'<a href="'.$url.'">'.$formattedUrl.'</a>'.$space2;
}

/* thanks http://stackoverflow.com/questions/1925455/how-to-mimic-stackoverflow-auto-link-behavior */

function auto_link_text($text) {
    $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    return preg_replace_callback($pattern, 'auto_link_text_callback', $text);
}

function auto_link_text_callback($matches) {
    $max_url_length = 50;
    $max_depth_if_over_length = 2;
    $ellipsis = htmlentities('&hellip;');

    $url_full = $matches[0];
    $url_short = '';

    if (strlen($url_full) > $max_url_length) {
        $parts = parse_url($url_full);
        $url_short = $parts['scheme'] . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';

        $path_components = explode('/', trim($parts['path'], '/'));
        foreach ($path_components as $dir) {
            $url_string_components[] = $dir . '/';
        }

        if (!empty($parts['query'])) {
            $url_string_components[] = '?' . $parts['query'];
        }

        if (!empty($parts['fragment'])) {
            $url_string_components[] = '#' . $parts['fragment'];
        }

        for ($k = 0; $k < count($url_string_components); $k++) {
            $curr_component = $url_string_components[$k];
            if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                if ($k == 0 && strlen($url_short) < $max_url_length) {
                    // Always show a portion of first directory
                    $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                }
                $url_short .= $ellipsis;
                break;
            }
            $url_short .= $curr_component;
        }

    } else {
        $url_short = $url_full;
    }

    return "<a href=\"$url_full\">$url_short</a>";
}

/* generic function for formatting paragraphs of HTML text */
function formatParagraphs($paragraphs) {
	// regex modified from http://snipplr.com/view/36992/improvement-of-url-interpretation-with-regex/
	$pattern = '@(^|\s|&#10;)(https?://.+?)(\s|$|&#10;)@';
	$paragraphs = preg_replace_callback(
		$pattern,
		'truncateURL',
		$paragraphs
	);
	//$paragraphs = auto_link_text($paragraphs);
	$paragraphs = str_replace("&#10;","<br />",$paragraphs);
	$paragraphs = html_entity_decode($paragraphs, ENT_QUOTES, 'UTF-8');
	return $paragraphs;
}

function formatUserPicture($userID=null, $size='large') {
	if($userID == null) return null;
	$user = User::load($userID);
	if($size == 'large') {
		return ('<a class="picture large" href="'.Url::user($user->getID()).'" title="'.$user->getUsername().'"><img src="'.Url::userPictureLarge($user->getID()).'" /></a>');
	} elseif($size == 'small') {
		return ('<a class="picture small" href="'.Url::user($user->getID()).'" title="'.$user->getUsername().'"><img src="'.Url::userPictureSmall($user->getID()).'" /></a>');
	} else {
		return '';
	}
}

function formatBlankUserPicture($url=null, $size='large') {
	if($size == 'large') {
		return ('<a class="picture" href="'.$url.'"><img src="'.Url::blankUserPictureLarge().'" /></a>');
	} elseif($size == 'small') {
		return ('<a class="picture small" href="'.$url.'"><img src="'.Url::blankUserPictureSmall().'" /></a>');
	} else {
		return '';
	}
}

function formatUserLink($userID=null, $projectID=null)
{
	if($userID == null) return null;
	$user = User::load($userID);
	$formatted = '<a href="'.Url::user($userID).'">'.$user->getUsername().'</a>';
	// add star to trusted users
	if($projectID != null) {
		if( (ProjectUser::isTrusted($userID, $projectID)) ||
			(ProjectUser::isCreator($userID, $projectID)) ){
			$formatted .= '<a href="'.Url::help().'#help-roles" title="trusted member">*</a>';
		}
	}
	return $formatted;
}

function formatUserStrip($userID=null, $projectID=null) {
	if( ($userID === null) || ($projectID === null) ) return null;
	
	$numTasksLed = formatCount(count(Task::getByLeaderID($projectID, $userID)), 'task', 'tasks', 'no');
	$numTaskContributions = formatCount(count(Update::getByUserID($userID, $projectID)), 'task contribution', 'task contributions', 'no');
	$numTaskComments = formatCount(count(Comment::getByUserID($userID, $projectID)), 'task comment', 'task comments', 'no');
	
	$strip = $numTasksLed.' led <span class="slash">/</span> '.$numTaskContributions.' <span class="slash">/</span> '.$numTaskComments;
	return ($strip);
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

	
function formatAcceptedStatus($status=null) {
	if($status === null) return null;
	if($status == Accepted::STATUS_ACCEPTED)
		return "started";
	elseif($status == Accepted::STATUS_FEEDBACK)
		return "seeking feedback";
	elseif($status == Accepted::STATUS_COMPLETED)
		return "finished";
	elseif($status == Accepted::STATUS_PROGRESS)
		return "working";
	else
		return "stopped";		
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


function formatSpecs($specs) {
	if(empty($specs)) return null;
	//$specs = filter_var($specs,FILTER_SANITIZE_SPECIAL_CHARS);
	$lines = explode("&#10;",$specs); // line feeds

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
	//$rules = filter_var($rules,FILTER_SANITIZE_SPECIAL_CHARS);
	$lines = explode("&#10;",$rules); // line feeds

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

/* From http://cubiq.org/the-perfect-php-clean-url-generator */
function toAscii($str, $replace=array(), $delimiter='-')
{
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}