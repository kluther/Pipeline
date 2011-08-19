<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Register");
$fork->startBlockSet('body');

?>

<div class="left">



<?php
	$SOUP->render('site/partial/register', array(
		));
?>

</div><!-- end .left -->

<div class="right">

</div><!-- end .right -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');