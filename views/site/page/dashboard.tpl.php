<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Dashboard");
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	$SOUP->render('site/partial/projects', array(
		'title' => 'Your Projects'
	));
?>

<?php
	$SOUP->render('site/partial/tasks', array(
		'title' => 'Your Tasks'
	));
?>


<?php
	$SOUP->render('site/partial/invitations', array(
	));
?>

</div>

<div class="right">

<?php
	$SOUP->render('site/partial/activity', array(
		'size' => 'small',
		'showProject' => true
	));
?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');