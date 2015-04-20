<?php

$project = $SOUP->get('project');
$events = $SOUP->get('events');
// $yourDiscussions = $SOUP->get('yourDiscussions');
// $moreDiscussions = $SOUP->get('moreDiscussions');
$reflections = $SOUP->get('reflections');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "reflections");
$fork->set('breadcrumbs', Breadcrumbs::reflections($project->getID()));
$fork->startBlockSet('body');

?>

<td class="left">


<?php
	$SOUP->render('project/partial/reflections',array(
		'reflections' => $reflections,
		'size' => 'large'
	));
?>

</td>

<td class="right">


<?php
	$SOUP->render('site/partial/activity', array(
	//	'title' => "Recent Activity",
		'size' => 'small',
		'olderURL' => Url::activityReflections($project->getID()),
		'class' => 'subtle'
		));
?>

</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');