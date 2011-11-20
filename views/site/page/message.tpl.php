<?php

$message = $SOUP->get('message');

$fork = $SOUP->fork();
$fork->set('pageTitle', 'Inbox');
$fork->set('headingURL', Url::message($message->getID()));
$fork->startBlockSet('body');

unset($message);

?>

<td class="left">

<?php
	$SOUP->render('site/partial/message', array(
	));
?>

</td>

<td class="right">

</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');