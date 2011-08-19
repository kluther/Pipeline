<?php

$project = $SOUP->get('project');
$banned = $SOUP->get('banned');

$fork = $SOUP->fork();
$fork->startBlockSet('body');

$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));
$fork->set('selected', "people");
$fork->set('breadcrumbs', Breadcrumbs::banned($project->getID()));
$fork->set('project', $project);

?>

<?php

$SOUP->render('project/partial/users',array(
	'style' => 'list',
	'users' => $banned,
	'description' => 'Banned users aren\'t allowed to contribute to this project.',
	'title' => 'Banned'
	));

$fork->endBlockSet();
$fork->render('site/partial/page');

