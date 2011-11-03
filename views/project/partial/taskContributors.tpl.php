<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$task = $SOUP->get('task');
$joined = $SOUP->get('accepted');
$id = $SOUP->get('id', 'contributors');
$hasJoinedTask = $SOUP->get('hasJoinedTask', false);

// can user join or leave task?
$hasLeavePermission = false;
$hasJoinPermission = false;

if(Session::isLoggedIn() &&
	!$project->isBanned(Session::getUserID())) {
	if($hasJoinedTask) {
		$hasLeavePermission = true;
	} else {
		$hasJoinPermission = true;
	}
}

// num joined
$numJoined = $task->getNumAccepted();

// num needed
$numNeeded = $task->getNumNeeded();

if(empty($numNeeded))
	$numNeeded = '&#8734; people';
else {
	$numNeeded = $numNeeded - $numJoined;
	if($numNeeded < 0) $numNeeded = 0;
	$numNeeded = formatCount($numNeeded,'person','people');
}
$numJoined = formatCount($numJoined,'person','people');

$fork = $SOUP->fork();
$fork->set('title', 'Task Members');
$fork->set('id', $id);
if($hasJoinPermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Join Task');
} elseif($hasLeavePermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Leave Task');
}

$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function() {

<?php if($hasJoinPermission): ?>

	var btnJoin = $('#<?= $id ?> .createButton');
	$(btnJoin).click(function() {
		buildPost({
			'processPage': '<?= Url::taskProcess($task->getID()) ?>',
			'info':{
				'action': 'accept'
			},
			'buttonID': btnJoin
		});
	});
	
<?php elseif($hasLeavePermission): ?>

	var btnLeave = $('#<?= $id ?> .createButton');
	$(btnLeave).click(function() {
		buildPost({
			'processPage': '<?= Url::taskProcess($task->getID()) ?>',
			'info':{
				'action': 'release'
			},
			'buttonID': btnLeave
		});
	});	

<?php endif; ?>
	
});

</script>

<div class="view">

<p><?= $numNeeded ?> needed <span class="slash">/</span> <?= $numJoined ?> joined</p>

<?php

if( !empty($joined) ) {
	echo '<div class="line"></div>';
	echo '<ul class="segmented-list users">';
}

// contributors

if($joined != null) {
	foreach($joined as $j) {
		echo '<li>';
		echo formatUserPicture($j->getCreatorID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($j->getCreatorID(), $project->getID()).'</h6>';
		$numUpdates = count($j->getUpdates());
		$latestUpdate = $j->getLatestUpdate();
		if(!empty($latestUpdate)) {
			echo '<p class="secondary contribution"><a href="'.Url::update($latestUpdate->getID()).'">last contributed '.formatTimeTag($latestUpdate->getDateCreated()).'</a> <span class="slash">/</span> '.$numUpdates.' total</p>';
		//	echo '<h6 class="primary"><a href="'.Url::update($latestUpdate->getID()).'">'.$latestUpdate->getTitle().'</a></h6>';
		//	echo '<p class="secondary">posted '.formatTimeTag($latestUpdate->getDateCreated()).' by '.formatUserLink($latestUpdate->getCreatorID(), $latestUpdate->getProjectID()).'</p>';
		} else {
			echo '<p class="secondary">no contributions <span class="slash">/</span> joined '.formatTimeTag($j->getDateCreated()).'</p>';
		}
		echo '</li>';
	}
}

if( !empty($joined) ) {
	echo '</ul>';
}

?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');