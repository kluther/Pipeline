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

<script type="text/javascript">

	$(document).ready(function() {
		$('#yourTasks .createButton').click(function() {
			window.location = '<?= Url::taskNew($project->getID()) ?>';
		});
		$('#moreTasks .createButton').click(function() {
			window.location = '<?= Url::taskNew($project->getID()) ?>';
		});		
	});

</script>

<?php
	$SOUP->render('project/partial/tasks', array(
		'id' => 'yourTasks',
		'tasks' => $yourTasks,
		'title' => 'Your Tasks',
		'creatable' => true,
		'createLabel' => "New Task"
	));
?>

<?php
	$SOUP->render('project/partial/tasks', array(
		'id' => 'moreTasks',
		'tasks' => $moreTasks,
		'title' => 'More Tasks',
		'creatable' => true,
		'createLabel' => "New Task"
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