<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Find Projects");
$fork->set('headingURL', Url::findProjects());
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/projects', array(
		'title' => 'Public Projects'
	));
?>

</td>

<td class="right"> </td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');