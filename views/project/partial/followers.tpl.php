<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$followers = $SOUP->get('followers');

// admin, creator, trusted has permission
$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

$fork = $SOUP->fork();
$fork->set('title', 'Followers');
$fork->set('id', 'followers');
$fork->startBlockSet('body');

?>

<?php if($hasPermission): ?>

<script type="text/javascript">

$(document).ready(function(){
	
	$("#followers input.ban").click(function(){
		var id = $(this).attr('id').substring(4); // 'ban-'
		buildPost({
			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',
			'info': {
				'action': 'ban',
				'userID': id
			},
			'buttonID': $(this)
		});
	});		
	
});

</script>

<?php endif; ?>	

<ul class="segmented-list users">

<?php

if(empty($followers)) {
	echo '<li class="none">(none)</li>';
} else {
	foreach($followers as $f) {
		echo '<li>';		
		if($hasPermission) {
			echo '<input id="ban-'.$f->getID().'" type="button" class="ban" value="Ban" />';
		}
		echo formatUserPicture($f->getID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($f->getID()).'</h6>';
		echo '<p class="secondary">follower</p>';
		echo '</li>';
	}
}

?>

</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');