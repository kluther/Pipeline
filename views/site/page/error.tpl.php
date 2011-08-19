<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Error");
///$fork->set('breadcrumbs', Breadcrumbs::error());
$fork->startBlockSet('body');

?>

<div class="left">

page not found

</div>

<div class="right">

right

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');