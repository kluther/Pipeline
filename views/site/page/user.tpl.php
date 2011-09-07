<?php
$user = $SOUP->get('user');
$tasks = $SOUP->get('tasks');

$fork = $SOUP->fork();

$fork->set('pageTitle', $user->getUsername());
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	$SOUP->render('site/partial/profile', array(
	));
?>

<?php
	$SOUP->render('site/partial/userTasks', array(
		'user' => $user,
		'tasks' => $tasks,
		'hasPermission' => false
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