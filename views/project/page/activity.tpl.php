<?php

$project = $SOUP->get('project');
$filter = $SOUP->get('filter');
$sparklineData = $SOUP->get('sparklineData');

$fork = $SOUP->fork();

$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));
$fork->set('selected', "activity");
$fork->set('breadcrumbs', Breadcrumbs::activity($project->getID()));
$fork->set('project', $project);
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('/project/partial/sparkline',array(
		'data' => $sparklineData,
		'filter' => $filter
		));
?>
	
<?php
	$SOUP->render('site/partial/activity',array(
		'size' => 'large'
		));
?>

</td>

<td class="right">


<?php
	$SOUP->render('project/partial/discussions',array(
		'title' => 'Recent Discussions',
		'cat' => 'activity',
		'size' => 'small',
		'class' => 'subtle'
	));
?>

</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');