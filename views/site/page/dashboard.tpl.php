<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Dashboard");
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	$SOUP->render('/site/partial/projects', array(
		'title' => 'Your Projects'
	));

?>

</div>

<div class="right">

<?php
	$SOUP->render('site/partial/activity', array(
		'size' => 'small'
	));
?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');