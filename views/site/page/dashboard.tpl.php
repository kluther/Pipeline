<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Dashboard");
$fork->startBlockSet('body');

?>

<td class="left">


<?php
	$SOUP->render('site/partial/projects', array(
		'title' => 'Your Projects'
	));
?>

<?php
	$SOUP->render('site/partial/userTasks', array(
		'title' => 'Your Tasks',
		'user' => User::load(Session::getUserID()),
		'hasPermission' => false
	));
?>

</td>

<td class="right">

<?php
	$SOUP->render('site/partial/invitations', array(
	));
?>


<?php
	$SOUP->render('site/partial/activity', array(
		'showProject' => true,
		'title' => 'Recent Activity in Your Projects',
		'size' => 'small',
		'class' => 'subtle'
	));
?>


</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');