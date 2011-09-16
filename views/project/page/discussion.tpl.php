<?php

$project = $SOUP->get('project');
$discussion = $SOUP->get('discussion');
$replies = $SOUP->get('replies');
$events = $SOUP->get('events');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "discussions");
$fork->set('breadcrumbs', Breadcrumbs::discussion($discussion->getID()));
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('project/partial/discussion', array(
		'discussion' => $discussion,
		'replies' => $replies
	));
?>

</td>

<td class="right">

<?php
	$SOUP->render('project/partial/yourRole', array());
?>

<?php
	$SOUP->render('site/partial/activity', array(
		'events' => $events,
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