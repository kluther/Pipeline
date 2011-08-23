<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$updates = $SOUP->get('updates', array());
$update = $SOUP->get('update', null);
$title = $SOUP->get('title', 'Updates');
//$accepted = $SOUP->get('accepted');
$size = $SOUP->get('size', 'large');
$task = $SOUP->get('task');
$taskUpdates = $SOUP->get('taskUpdates', false);

$updateID = ($update != null) ? $update->getID() : null;

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('creatable', true);
if($size == 'small') {
	$fork->set('createLabel', 'New');
} else {
	$fork->set('createLabel', 'New Update');
}
$fork->startBlockSet('body');
?>

<script type="text/javascript">

$(document).ready(function(){
	$('#progress input.createButton').mousedown(function(){
		window.location = '<?= Url::updateNew($task->getID()) ?>';
	});
});

</script>

<?php
if($updates != null) {
	echo '<ul class="segmented-list updates">';
	foreach($updates as $u) {

			echo '<li>';
			echo '<p class="primary"><a href="'.Url::update($u->getID()).'">'.$u->getTitle().'</a>';
			if($u->isLatestUpdate()) {
				$accept = Accepted::load($u->getAcceptedID());
				$statusName = Accepted::getStatusName($accept->getStatus());
				echo '&nbsp;<span class="status">'.$statusName.'</span>';
			}
			echo '</p>'; // .primary
			
			echo '<p class="secondary">';
			echo formatTimeTag($u->getDateCreated());
			if($taskUpdates) {
				echo ' by '.formatUserLink($u->getCreatorID());
			} else {
				$comments = $u->getComments();
				echo ' <span class="slash">/</span> '.formatCount(count($comments),'comment','comments','no');
			}
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