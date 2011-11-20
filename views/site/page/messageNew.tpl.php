<?php

$fork = $SOUP->fork();
$fork->set('pageTitle', 'Inbox');
$fork->set('headingURL', Url::messageNew());
$fork->startBlockSet('body');
?>

<td class="left">

<?php
	$SOUP->render('site/partial/messageNew', array(
	));
?>

</td>

<td class="right">

</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');