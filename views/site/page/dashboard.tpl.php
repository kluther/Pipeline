<?php

$yourProjects = $SOUP->get('yourProjects');
if(empty($yourProjects)) {
	$title = 'Projects to Join';
	$projects = $SOUP->get('publicProjects');
	$user = null;
} else {
	$title = 'Your Projects';
	$projects = $yourProjects;
	$user = Session::getUser();
}

$fork = $SOUP->fork();
$fork->set('pageTitle', "Dashboard");
$fork->set('headingURL', Url::dashboard());
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/projects', array(
		'title' => $title,
		'projects' => $projects,
		'user' => $user,
		'footer' => '<p><a href="'.Url::findProjects().'">More Projects &raquo;</a></p>'
	));
?>

<?php
	$SOUP->render('project/partial/tasks', array(
		'title' => 'Your Tasks',
		'user' => Session::getUser(),
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