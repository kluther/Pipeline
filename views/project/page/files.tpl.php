<?php

$project = $SOUP->get('project');

$fork = $SOUP->fork();

$fork->set('project', $project);
$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));

$fork->set('selected', "files");
$fork->set('breadcrumbs', Breadcrumbs::files($project->getID()));
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('project/partial/commonFiles', array(
	));
?>

<?php
	$SOUP->render('project/partial/taskFiles', array(
	));
?>

</td>

<td class="right">



</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');