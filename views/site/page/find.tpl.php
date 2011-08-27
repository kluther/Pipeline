<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Find Projects");
$fork->startBlockSet('body');

?>

<div class="left">

<?php
	$SOUP->render('site/partial/lookingForHelp', array(
	));
?>

</div>

<div class="right">

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');