<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Register");
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/register', array(
		));
?>

</td><!-- end .left -->

<td class="right">

</td><!-- end .right -->

<td class="extra"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');