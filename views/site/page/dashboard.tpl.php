<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Dashboard");
$fork->startBlockSet('body');

?>

<div class="left">

<h1>Dashboard</h1>

</div>

<div class="right">

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');