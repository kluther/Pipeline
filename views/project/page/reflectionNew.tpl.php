<?php
$project = $SOUP->get('project');
$yourReflections = $SOUP->get('yourReflections');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "reflections");
$fork->set('breadcrumbs', Breadcrumbs::reflectionNew($project->getID()));

$fork->startBlockSet('body');
?>

<td class="left">

<?php
	$SOUP->render('project/partial/reflectionNew', array(
		));
?>

</td>

<td class="right">

<?php
	$SOUP->render('project/partial/discussions',array(
		'discussions' => $yourReflections,
		'size' => 'small',
		'title' => 'Your Reflections',
		'hasPermission' => false
	));
?>

</td>


<?

$fork->endBlockSet();
$fork->render('site/partial/page');

