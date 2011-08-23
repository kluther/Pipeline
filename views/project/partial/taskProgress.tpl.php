<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$task = $SOUP->get('task');
//$accepted = $SOUP->get('accepted', array());
$updates = $SOUP->get('updates', array());

$fork = $SOUP->fork();
$fork->set('title', 'Latest Task Updates');
$fork->set('creatable', true);
$fork->set('createLabel', 'New Update');

$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#progress input.createButton').mousedown(function(){
		window.location = '<?= Url::updateNew($task->getID()) ?>';
	});
});

</script>

<?php
if($updates != null) {
	echo '<ul class="segmented-list accepted">';
	foreach($updates as $u) {
		$accept = Accepted::load($u->getAcceptedID());
		echo '<li>';
		//echo '<a class="picture small" href="'.Url::user($task->getCreatorID()).'"><img src="'.Url::userPictureSmall($task->getCreatorID()).'" /></a> ';
		echo '<p class="title"><a href="'.Url::update($u->getID()).'">'.$u->getTitle().'</a></p>';
		echo '<p class="updates">'.formatTimeTag($u->getDateCreated());
		echo ' by '.formatUserLink($u->getCreatorID());
		echo '<p class="status"><span>'.Accepted::getStatusName($accept->getStatus()).'</span></p>';
		//echo ' <span class="slash">/</span> <a href="'.Url::update($update->getID()).'">'.formatCount(count($updates),'update','updates').' total</a></p>';
		echo '</li>';
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