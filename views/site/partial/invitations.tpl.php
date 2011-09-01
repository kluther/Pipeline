<?php

$invitations = $SOUP->get('invitations', array());

$fork = $SOUP->fork();
$fork->set('title', 'Your Invitations');
$fork->startBlockSet('body');

if($invitations != null) {
	echo '<ul class="segmented-list invitations">';
	foreach($invitations as $i) {
		echo '<li>';
		
		echo '</li>';
	}
	echo '</ul>';
} else {
	echo '<p>(none)</p>';
}

$fork->endBlockSet();
$fork->render('site/partial/panel');