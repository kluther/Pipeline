<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$task = $SOUP->get('task');
$accepted = $SOUP->get('accepted', array());

$fork = $SOUP->fork();
$fork->startBlockSet('body');
$fork->set('title', 'Latest Task Updates');

?>
<?php
if($accepted != null) {
	echo '<ul class="segmented-list accepted">';
	foreach($accepted as $accept) {
		$updates = Update::getByAcceptedID($accept->getID());
		if($updates != null) {
			echo '<li>';
			$latestUpdate = reset($updates);

			//echo '<a class="picture small" href="'.Url::user($task->getCreatorID()).'"><img src="'.Url::userPictureSmall($task->getCreatorID()).'" /></a> ';
			echo '<p class="title"><a href="'.Url::update($latestUpdate->getID()).'">'.$latestUpdate->getTitle().'</a></p>';
			echo '<p class="updates">'.formatTimeTag($latestUpdate->getDateCreated());
			echo ' by '.formatUserLink($accept->getCreatorID());
			echo '<p class="status"><span>'.Accepted::getStatusName($accept->getStatus()).'</span></p>';
			//echo ' <span class="slash">/</span> <a href="'.Url::update($latestUpdate->getID()).'">'.formatCount(count($updates),'update','updates').' total</a></p>';
			echo '</li>';
		}
	}
	echo '</ul>';
} else {
	echo '<p>(none)</p>';
}
?>
</ul>
<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');