<?php
$user = $SOUP->get('user');
$tasks = $SOUP->get('tasks');

$fork = $SOUP->fork();

$fork->set('pageTitle', $user->getUsername());
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/profile', array(
	));
?>

</td>

<td class="extra">

<?php
	$SOUP->render('site/partial/userTasks', array(
		'user' => $user,
		'tasks' => $tasks,
		'hasPermission' => false,
		'size' => 'small'
	));
?>

</td>

<td class="right">

<?php
	$SOUP->render('site/partial/activity', array(
		'size' => 'small',
		'showProject' => true
	));
?>

</td>



<?php

$fork->endBlockSet();
$fork->render('site/partial/page');