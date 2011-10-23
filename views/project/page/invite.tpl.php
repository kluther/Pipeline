<?php
$project = $SOUP->get('project');

$fork = $SOUP->fork();

$fork->set('pageTitle', $project->getTitle());
$fork->set('headingURL', Url::project($project->getID()));
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	
	$SOUP->render('project/partial/invite', array());
	
?>

</td>

<td class="right">



</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');