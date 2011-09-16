<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

//$user = $SOUP->get('user', User::load(Session::getUserID()));
$updates = $SOUP->get('updates', array());
$update = $SOUP->get('update', null);
$title = $SOUP->get('title', 'Updates');
//$creatable = $SOUP->get('creatable', true);
$id = $SOUP->get('id', 'updates');
$accepted = $SOUP->get('accepted');
//print_r( $accepted );
$size = $SOUP->get('size', 'large');
$task = $SOUP->get('task');
//$taskUpdates = $SOUP->get('taskUpdates', false);
//$updateID = ($update != null) ? $update->getID() : null;
$hasPermission = $SOUP->get('hasPermission', null);

// allow value to be passed in
if($hasPermission === null) {
	$hasPermission = false;
	if($accepted->getCreatorID() == Session::getUserID()) {
			// only works if we're looking at this user's updates
			$hasPermission = true;
	}
}

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('creatable', $hasPermission);
$fork->set('createLabel', 'Contribute');
// if($size == 'small') {
	// $fork->set('createLabel', 'New');
// } else {
	// $fork->set('createLabel', 'New Update');
// }
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
			echo '<h6 class="primary"><a href="'.Url::update($u->getID()).'">'.$u->getTitle().'</a>';
			if($u->isLatestUpdate()) {
				$accept = Accepted::load($u->getAcceptedID());
				$statusName = Accepted::getStatusName($accept->getStatus());
				echo ' <span class="status">'.$statusName.'</span>';
			}
			echo '</h6>'; // .primary
			
			echo '<p class="secondary">';
			echo 'posted '.formatTimeTag($u->getDateCreated());
			$comments = $u->getComments();
			echo ' <span class="slash">/</span> '.formatCount(count($comments),'comment','comments','no');
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