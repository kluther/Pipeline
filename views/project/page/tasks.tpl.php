<?php

$project = $SOUP->get('project');
$moreTasks = $SOUP->get('moreTasks');
$yourTasks = $SOUP->get('yourTasks');
$tasks = $SOUP->get('tasks');
$events = $SOUP->get('events');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "tasks");
$fork->set('breadcrumbs', Breadcrumbs::tasks($project->getID()));
$fork->startBlockSet('body');

?>

<div class="left">

<?php if(Session::isLoggedIn()): ?>

<?php
	$SOUP->render('site/partial/userTasks', array(
		'id' => 'yourTasks',
		'tasks' => $yourTasks,
		'title' => 'Your Tasks',
		'user' => User::load(Session::getUserID())
	));
?>

<?php
	$SOUP->render('project/partial/tasks', array(
		'id' => 'moreTasks',
		'tasks' => $moreTasks,
		'title' => 'More Tasks'
	));
?>

<?php else: ?>

<?php
	$SOUP->render('project/partial/tasks', array(
		'tasks' => $tasks,
		'title' => 'Tasks'
	));
?>

<?php endif; ?>


</div>

<div class="right">

<?php
	$SOUP->render('project/partial/discussions',array(
		'title' => 'Recent Discussions',
		'cat' => 'tasks',
		'size' => 'small'
	));
?>

<?php
	$SOUP->render('site/partial/activity', array(
		'title' => "Recent Activity",
		'events' => $events,
		'size' => 'small',
		'olderURL' => Url::activityTasks($project->getID())
		));
?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');