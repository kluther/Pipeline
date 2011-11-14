<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$cat = $SOUP->get('cat');
$title = $SOUP->get('title', 'Discussions');
$hasPermission = $SOUP->get('hasPermission', null);
$discussions = $SOUP->get('discussions', array());
$class = $SOUP->get('class');
$size = $SOUP->get('size');

// allow values to be passed in
if($hasPermission === null) {
	// any logged-in user may discuss
	$hasPermission = ( Session::isLoggedIn() &&
						!$project->isBanned(Session::getUserID()) );
}

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('class', $class .= ' discussions');
$fork->set('creatable', $hasPermission);
$fork->set('createLabel', 'New Discussion');

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

$fork->startBlockSet('body');
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

<?php

if(empty($discussions)) {
	echo '<p>(none)</p>';
} elseif($size == 'small') {
	echo '<ul class="segmented-list discussions">';
	foreach($discussions as $d) {
		echo '<li>';
		$cssLock = ($d->getLocked()) ? ' locked' : '';
		echo '<h6 class="primary'.$cssLock.'"><a href="'.Url::discussion($d->getID()).'">'.$d->getTitle().'</a></h6>';
		echo '<p class="secondary">';
		$numReplies = count($d->getReplies());
		echo formatCount($numReplies,'reply','replies','no') . ' <span class="slash">/</span> ';
		if($numReplies>0) {
			$lastReply = $d->getLastReply();
			echo 'last reply '.formatTimeTag($lastReply->getDateCreated()).' by '.formatUserLink($lastReply->getCreatorID(), $project->getID());
		} else {
			echo 'posted '.formatTimeTag($d->getDateCreated()).' by '.formatUserLink($d->getCreatorID(), $project->getID());
		}
		echo '</p>';
		echo '</li>';
	}
	echo '</ul>';
} else {
?>
	<table class="items discussions">
		<tr>
			<th style="padding-left: 22px;">Discussion</th>
			<th>Replies</th>
			<th>Last Reply</th>
			<th>Category</th>
		</tr>
<?php
	foreach($discussions as $d) {
		echo '<tr>';
		echo '<td class="title">';
		$cssLock = ($d->getLocked()) ? ' class="locked"' : '';
		echo '<h6'.$cssLock.'><a href="'.Url::discussion($d->getID()).'">'.$d->getTitle().'</a></h6>';
		echo '<p>by '.formatUserLink($d->getCreatorID(), $d->getProjectID()).'</p>';
		echo '</td>';
		$numReplies = (count($d->getReplies()));
		echo '<td class="replies">'.$numReplies.'</td>';
		$lastReply = $d->getLastReply();
		if(!empty($lastReply)) {
			$lrDate = formatTimeTag($lastReply->getDateCreated());
			$lrCreator = formatUserLink($lastReply->getCreatorID(), $lastReply->getProjectID());
			echo '<td class="last-reply">'.$lrDate.'<br />by '.$lrCreator.'</td>';
		} else {
			echo '<td class="last-reply">--</td>';
		}
		$category = ($d->getCategory() != null) ? '<span>'.formatSectionLink($d->getCategory(), $d->getProjectID()).'</span>' : '--';
		echo '<td class="category">'.$category.'</td>';
		echo '</tr>';
	}
?>
	</table>
<?php
}

$fork->endBlockSet();
$fork->render('site/partial/panel');
