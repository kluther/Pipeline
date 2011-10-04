<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$cat = $SOUP->get('cat');
$title = $SOUP->get('title', 'Discussions');
$hasPermission = $SOUP->get('hasPermission', null);
$discussions = $SOUP->get('discussions', array());
$size = $SOUP->get('size');

// allow values to be passed in
if($hasPermission === null) {
	// any logged-in user may discuss
	$hasPermission = ( Session::isLoggedIn() &&
						!$project->isBanned(Session::getUserID()) );
}

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('class', 'discussions');
$fork->set('creatable', $hasPermission);

// generate URL for new discussion, if has permission
if($hasPermission) {
	$newURL = Url::discussionNew($project->getID());
	$newURL .= ($cat != null) ? '/'.$cat : '';
}

// if($size == 'small') {
// //	$fork->set('createLabel', 'New');
	// $newURL = Url::discussionNew($project->getID()).'/'.$cat;
// } else {
// //	$fork->set('createLabel', 'New Discussion');
	// $newURL = Url::discussionNew($project->getID());
// }
$fork->set('createLabel', 'New Discussion');

?>

<?php if($hasPermission): ?>

<script type="text/javascript">

$(document).ready(function(){
	$('div.discussions .createButton').click(function(){
		window.location = '<?= $newURL ?>';
	});
});

</script>

<?php endif; ?>

<?

$fork->startBlockSet('body');

if($discussions != null) {
	echo '<ul class="segmented-list discussions">';
	foreach($discussions as $discussion)
	{
		$url = Url::discussion($discussion->getID());
		$title = $discussion->getTitle();
		$replies = $discussion->getReplies();
		
		echo '<li>';

		// title
		echo '<h6 class="primary"><a href="'.$url.'">'.$title.'</a>';
		
		// status
		if( ($discussion->getCategory() != null) && ($size != 'small')) // discussion category, if exists
			echo ' <span class="status">'.formatSectionLink($discussion->getCategory(),$discussion->getProjectID()).'</span>';
		
		echo '</h6>'; // .primary
		
		// reply info
		echo '<p class="secondary">';
		
		if(count($replies) > 0) {
			if($size != 'small')
				echo formatCount(count($replies),'reply','replies','no').' <span class="slash">/</span>'; // number of replies
			$latestReply = reset($replies);				
			echo ' last reply '.formatTimeTag($latestReply->getDateCreated()).' by '.formatUserLink($latestReply->getCreatorID(), $project->getID()); // last reply
		} else {
			echo 'posted '.formatTimeTag($discussion->getDateCreated()).' by '.formatUserLink($discussion->getCreatorID(), $project->getID());
		}
		
		echo '</p>'; // .secondary
		echo '</li>';
	}
	echo '</ul>';
} else {
	echo "<p>(none)</p>";
}


$fork->endBlockSet();
$fork->render('site/partial/panel');
