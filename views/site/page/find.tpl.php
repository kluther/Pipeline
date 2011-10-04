<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Find Projects");
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$SOUP->render('site/partial/lookingForHelp', array(
	));
?>

</td>

<td class="right"> </td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');