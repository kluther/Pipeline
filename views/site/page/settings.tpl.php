<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Settings");
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	$SOUP->render('site/partial/notifications', array(
	));
?>

</div>

<div class="right">

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');