<?php

$project = $SOUP->get('project');
$creator = $SOUP->get('creator');
$organizers = $SOUP->get('organizers');
$contributors = $SOUP->get('contributors');
$followers = $SOUP->get('followers');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "people");
$fork->set('breadcrumbs', Breadcrumbs::people($project->getID()));
$fork->startBlockSet('body');

?>

<div class="left">


<?php
	$SOUP->render('project/partial/users', array(
		'creator' => $creator,
		'organizers' => $organizers,
		'contributors' => $contributors,
		'title' => 'Crew'
		));
?>

<?php
	$SOUP->render('project/partial/users', array(
		'title' => "Followers",
		'users' => $followers,
		'style' => 'list',
		'description' => "Interested in the project but haven't contributed yet."
		));
?>

<p><a href="<?= Url::banned($project->getID()) ?>">View Banned</a></p>


</div>

<div class="right">

<?php
	$SOUP->render('project/partial/discussions',array(
		'title' => 'Recent Discussions',
		'cat' => 'people',
		'size' => 'small'
	));
?>

<?php
	$SOUP->render('site/partial/activity', array(
		'title' => "Recent Activity",
		'size' => 'small',
		'olderURL' => Url::activityPeople($project->getID())
		));
?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');