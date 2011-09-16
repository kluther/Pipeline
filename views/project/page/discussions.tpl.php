<?php

$project = $SOUP->get('project');
$events = $SOUP->get('events');
$yourDiscussions = $SOUP->get('yourDiscussions');
$moreDiscussions = $SOUP->get('moreDiscussions');
$discussions = $SOUP->get('discussions');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "discussions");
$fork->set('breadcrumbs', Breadcrumbs::discussions($project->getID()));
$fork->startBlockSet('body');

?>

<td class="left">

<?php if(Session::isLoggedIn()): ?>

<?php
	$SOUP->render('project/partial/discussions',array(
		'discussions' => $yourDiscussions,
		'size' => 'large',
		'title' => 'Your Discussions'
	));
?>

<?php
	$SOUP->render('project/partial/discussions',array(
		'discussions' => $moreDiscussions,
		'size' => 'large',
		'title' => 'More Discussions'
	));
?>

<?php else: ?>

<?php
	$SOUP->render('project/partial/discussions',array(
		'discussions' => $discussions,
		'size' => 'large',
		'title' => 'Discussions'
	));
?>

<?php endif; ?>

</td>

<td class="right">

<?php
	$SOUP->render('project/partial/yourRole', array());
?>

<?php
	$SOUP->render('site/partial/activity', array(
		'title' => "Recent Activity",
		'size' => 'small',
		'olderURL' => Url::activityDiscussions($project->getID())
		));
?>

</td>

<td class="extra"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');