<?php

$project = $SOUP->get('project');
$events = $SOUP->get('events');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "details");
$fork->set('breadcrumbs', Breadcrumbs::details($project->getID()));
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	// $SOUP->render('project/partial/progress',array(
	// ));
?>

<?php
	$SOUP->render('project/partial/pitch',array(

	));
?>

<?php
	$SOUP->render('project/partial/specs',array(
	));
?>

<?php
	$SOUP->render('project/partial/rules',array(
	));
?>

</div>

<div class="right">

<?php
	$SOUP->render('project/partial/discussions',array(
		'title' => 'Recent Discussions',
		'cat' => 'basics',
		'size' => 'small'
	));
?>

<?php
	$SOUP->render('site/partial/activity', array(
		'title' => "Recent Activity",
		'size' => 'small',
		'events' => $events,
		'olderURL' => Url::activityDetails($project->getID())
		));
?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');