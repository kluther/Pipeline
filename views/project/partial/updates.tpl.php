<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$updates = $SOUP->get('updates', array());
$update = $SOUP->get('update', null);
$title = $SOUP->get('title', 'Updates');
$id = $SOUP->get('id', 'updates');
$accepted = $SOUP->get('accepted');
$size = $SOUP->get('size', 'large');
$task = $SOUP->get('task');
//$updateID = ($update != null) ? $update->getID() : null;
$hasPermission = $SOUP->get('hasPermission', null);

// allow value to be passed in
if($hasPermission === null) {
	$hasPermission = Accepted::hasAcceptedTask(Session::getUserID(), $task->getID());
}

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('creatable', $hasPermission);
$fork->set('createLabel', 'Contribute');
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript">

$(document).ready(function(){
	$('#updates input.createButton').click(function(){
		window.location = '<?= Url::updateNew($task->getID()) ?>';
	});
});

</script>

<?php endif; ?>

<?php
if($updates != null) {
	echo '<ul class="segmented-list updates">';
	foreach($updates as $u) {
			echo '<li>';
			echo formatUserPicture($u->getCreatorID(), 'small');
			echo '<h6 class="primary"><a href="'.Url::update($u->getID()).'">'.$u->getTitle().'</a>';
			if($u->isLatestUpdate()) {
				$accept = Accepted::load($u->getAcceptedID());
				$statusName = Accepted::getStatusName($accept->getStatus());
				echo ' <span class="status">'.$statusName.'</span>';
			}
			echo '</h6>'; // .primary
			
			echo '<p class="secondary">';
			echo 'posted '.formatTimeTag($u->getDateCreated());
			echo ' by '.formatUserLink($u->getCreatorID(), $project->getID());
			echo '</p>'; // .secondary
			echo '</li>';
	}
	echo '</ul>';
} else {
	echo "<p>(none)</p>";
}

?>


<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');