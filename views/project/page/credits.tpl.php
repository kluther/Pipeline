<?php

$project = $SOUP->get('project');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "people");
$fork->set('breadcrumbs', Breadcrumbs::people($project->getID()));
$fork->startBlockSet('body');

?>

<td class="left">
<?php
	$SOUP->render('project/partial/members', array(
	));
?>

<?php
//	$SOUP->render('project/partial/followers', array(
//	));
?>

<?php
	$SOUP->render('project/partial/banned', array(
	));
?>

</td>

<td class="right">

<?php
	$SOUP->render('project/partial/discussions',array(
		'title' => 'Recent Discussions',
		'cat' => 'people',
		'size' => 'small',
		'class' => 'subtle'
	));
?>

<?php
	$SOUP->render('site/partial/activity', array(
	//	'title' => "Recent Activity",
		'size' => 'small',
		'olderURL' => Url::activityPeople($project->getID()),
		'class' => 'subtle'
		));
?>

</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');