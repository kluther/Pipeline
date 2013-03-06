<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects');

$fork = $SOUP->fork();
$fork->set('pageTitle', "Admin Utilities");
$fork->set('title','Import Tasks');
$fork->set('headingURL', Url::utilities());
$fork->startBlockSet('body');

?>

<td class="left">

 <?php
    $SOUP->render('site/partial/adminUtilities');
 ?>
    
</td>

<td class="right">


</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/admin');