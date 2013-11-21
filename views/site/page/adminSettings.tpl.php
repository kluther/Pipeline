<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$type = $SOUP->get('types');

$fork = $SOUP->fork();
$fork->set('pageTitle', "Admin Settings");
$fork->set('title','Settings');
$fork->set('headingURL', Url::adminSettings());
$fork->startBlockSet('body');

?>

<td class="left">
 <?php
    $SOUP->render('site/partial/document', array());
 ?>
</td>

<td class="right">

 <?php
    $SOUP->render('site/partial/types');
 ?>

</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/admin');