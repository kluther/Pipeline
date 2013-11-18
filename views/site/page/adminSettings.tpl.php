<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects');

$fork = $SOUP->fork();
$fork->set('pageTitle', "Admin Settings");
$fork->set('title','Settings');
$fork->set('headingURL', Url::adminSettings());
$fork->startBlockSet('body');

?>

<td class="left">

 <?php
    $SOUP->render('site/partial/adminSettings');
 ?>
    
</td>

<td class="right">


</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/admin');