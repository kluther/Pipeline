<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Error");
$fork->set('headingURL', Url::error());
$fork->startBlockSet('body');

?>

<td class="left">

<p>Sorry, that page is not available.</p>

</td>


<td class="right"> </td>


<?php

$fork->endBlockSet();
$fork->render('site/partial/page');