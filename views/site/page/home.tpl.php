<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Home");
//$fork->set('breadcrumbs', Breadcrumbs::home());
$fork->startBlockSet('body');

?>

<div class="left">

left

</div>

<div class="right">

right

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');