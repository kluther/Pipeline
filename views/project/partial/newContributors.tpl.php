<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$trustedContributors = $SOUP->get('trustedContributors');
$untrustedContributors = $SOUP->get('untrustedContributors');

$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) );

$fork = $SOUP->fork();
$fork->set('title', 'Contributors');
$fork->set('id', 'contributors');
// only show Edit button if has permission AND there are contributors to edit
// if($hasPermission &&
	// (!empty($trustedContributors) || !empty($untrustedContributors)) ) {
	// $fork->set('editable', true);
	// $fork->set('editLabel', 'Edit Contributors');
// }
$fork->startBlockSet('body');


?>

<?php if($hasPermission): ?>

<script type="text/javascript">

$(document).ready(function(){

	// $("#contributors .editButton").click(function(){
		// var buttons = $("#contributors li.contributor input[type='button']");
		// if($(buttons).is(":hidden")) {
			// $(buttons).fadeIn();
		// } else {
			// $(buttons).hide();
		// }
	// });
	
	$("#contributors input.ban").click(function(){
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
	
	$("#contributors input.trust").click(function(){
		var id = $(this).attr('id').substring(6); // 'trust-'
		buildPost({
			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',
			'info': {
				'action': 'trust',
				'userID': id
			},
			'buttonID': $(this)
		});
	});	
	
	$("#contributors input.untrust").click(function(){
		var id = $(this).attr('id').substring(8); // 'untrust-'
		buildPost({
			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',
			'info': {
				'action': 'untrust',
				'userID': id
			},
			'buttonID': $(this)
		});
	});		
	
});

</script>

<?php endif; ?>	

<ul class="segmented-list users">

	<li>
		<?= formatUserPicture($project->getCreatorID(), 'small') ?>
		<h6 class="primary"><?= formatUserLink($project->getCreatorID()) ?>* (creator)</h6>
		<p class="secondary"><?= formatUserStrip($project->getCreatorID(), $project->getID()) ?></p>	
	</li>

<?php

if(!empty($trustedContributors)) {
	foreach($trustedContributors as $tc) {
		echo '<li class="trusted contributor">';		
		if($hasPermission) {
			echo '<input id="ban-'.$tc->getID().'" type="button" class="ban" value="Ban" /> <input id="untrust-'.$tc->getID().'" type="button" class="untrust" value="Untrust" />';
		}
		echo formatUserPicture($tc->getID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($tc->getID()).'*</h6>';
		echo '<p class="secondary">'.formatUserStrip($tc->getID(), $project->getID()).'</p>';
		echo '</li>';
	}
}

if(!empty($untrustedContributors)) {
	foreach($untrustedContributors as $uc) {
		echo '<li class="untrusted contributor">';
		if($hasPermission) {
			echo '<input id="ban-'.$uc->getID().'" type="button" class="ban" value="Ban" /> <input id="trust-'.$uc->getID().'" type="button" class="trust" value="Trust" />';
		}
		echo formatUserPicture($uc->getID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($uc->getID()).'</h6>';
		echo '<p class="secondary">'.formatUserStrip($uc->getID(), $project->getID()).'</p>';
		echo '</li>';
	}
}

?>

</ul>

<?php

$fork->endBlockSet();
$fork->startBlockSet('footer');

?>

<p>A star (*) indicates a <a href="<?= Url::help() ?>">trusted user</a>.</p>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');