<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$task = $SOUP->get('task');
$joined = $SOUP->get('accepted');

// num joined
$numJoined = $task->getNumAccepted();
$numJoined = formatCount($numJoined,'person','people');

// num needed
$numNeeded = $task->getNumNeeded();
if($numNeeded == 0)
	$numNeeded = '&#8734; people';
else {
	$numNeeded -= $numJoined;
	$numNeeded = formatCount($numNeeded,'person','people');
}

// can user join task or contribute to task?

$hasJoinPermission = false;
$hasContributePermission = false;

if($task->getStatus() == Task::STATUS_OPEN) {
	if(Session::isLoggedIn()) {
		if(Accepted::getByUserID(Session::getUserID(), $task->getID()) !== null) {
			// user has joined task
			if( Session::isAdmin() ||
				$project->isMember(Session::getUserID()) ||
				$project->isTrusted(Session::getUserID()) ||
				$project->isCreator(Session::getUserID()) ) {
				$hasContributePermission = true;
			}
		} else {
			// user hasn't joined task
			if( Session::isAdmin() || // must be admin, follower, or not banned
				!$project->isBanned(Session::getUserID()) ) {
				$hasJoinPermission = true;
			}
		}
	}
}

$fork = $SOUP->fork();
$fork->set('title', 'Contributors');
$fork->set('id', 'contributors');
if($hasJoinPermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Join Task');
} elseif($hasContributePermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Contribute');
}


$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function() {

<?php if($hasJoinPermission): ?>

	var btnJoin = $('#contributors .createButton');
	btnJoin.click(function() {
		buildPost({
			'processPage': '<?= Url::taskProcess($task->getID()) ?>',
			'info':{
				'action': 'accept'
			},
			'buttonID': btnJoin
		});
	});
	
<?php elseif($hasContributePermission): ?>

	$('#contributors .createButton').click(function(){
		window.location = '<?= Url::updateNew($task->getID()) ?>';
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
			echo '<p class="secondary">joined '.formatTimeTag($j->getDateCreated()).'</p>';
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