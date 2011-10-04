<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Settings");
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/notifications', array(
	));
?>

</td>

<td class="right">

</td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');