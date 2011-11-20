<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Register");
$fork->set('headingURL', Url::register());
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


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');