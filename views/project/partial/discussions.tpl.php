<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$cat = $SOUP->get('cat');
$discussions = $SOUP->get('discussions', array());
$size = $SOUP->get('size');

$fork = $SOUP->fork();
$fork->set('class', 'discussions');
$fork->set('creatable', true);
if($size == 'small') {
	$fork->set('createLabel', 'New');
	$newURL = Url::discussionNew($project->getID()).'/'.$cat;
} else {
	$fork->set('createLabel', 'New Discussion');
	$newURL = Url::discussionNew($project->getID());
}

?>

<script type="text/javascript">

$(document).ready(function(){
	$('div.discussions .createButton').click(function(){
		window.location = '<?= $newURL ?>';
	});
});

</script>

<?

$fork->startBlockSet('body');

if($discussions != null) {
	echo '<ul class="segmented-list discussions">';
	foreach($discussions as $discussion)
	{
		$url = Url::discussion($discussion->getID());
		$title = $discussion->getTitle();
		$replies = $discussion->getReplies("ASC"); // ascending sort so we get latest
		
		echo '<li>';
		if( ($discussion->getCategory() != null) && ($size != 'small')) // discussion category, if exists
			echo '<p class="section">posted in<br />'.formatSectionLink($discussion->getCategory(),$discussion->getProjectID()).'</p>';
		echo '<p class="title"><a href="'.$url.'">'.$title.'</a></p>'; // discussion title
		if(count($replies) > 0) {
			$latestReply = reset($replies);
			echo '<p class="replies">';
			if($size != 'small')
				echo formatCount(count($replies),'reply','replies','no').' <span class="slash">/</span>'; // number of replies
			echo ' last reply '.formatTimeTag($latestReply->getDateCreated()).' by '.formatUserLink($latestReply->getCreatorID()); // last reply
			echo '</p>';
		} else {
			echo '<p class="replies">posted '.formatTimeTag($discussion->getDateCreated()).' by '.formatUserLink($discussion->getCreatorID()).'</p>';
		}
		echo '</li>';
	}
	echo '</ul>';
} else {
	echo "<p>(none)</p>";
}


$fork->endBlockSet();
$fork->render('site/partial/panel');
