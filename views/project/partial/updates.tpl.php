<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$updates = $SOUP->get('updates', array());
$update = $SOUP->get('update', null);
$title = $SOUP->get('title', 'Updates');
$accepted = $SOUP->get('accepted');
$updateID = ($update != null) ? $update->getID() : null;

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->startBlockSet('body');

if($updates != null) {
	echo '<ul class="segmented-list accepted">';
	foreach($updates as $u) {
		if($updateID != $u->getID()) {
			echo '<li>';
			//echo '<p class="status">'.Accepted::getStatusName($accepted->getStatus()).'</p>';
			echo '<p class="title"><a href="'.Url::update($u->getID()).'">'.$u->getTitle().'</a></p>';
			echo '<p class="updates">'.formatTimeTag($u->getDateCreated());
			$comments = $u->getComments();
			echo ' <span class="slash">/</span> '.formatCount(count($comments),'comment','comments','no').'</p>';
			echo '</li>';
		}
	}
	echo '</ul>';
} else {
	echo "<p>(none)</p>";
}

?>


<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');