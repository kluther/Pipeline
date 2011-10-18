<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$invitations = $SOUP->get('invitations');
$unrespondedInvites = $SOUP->get('unrespondedInvites');

$fork = $SOUP->fork();
$fork->set('id', 'invitations');
$fork->set('title', 'Your Invitations');

// only show Show Responded button if we have at least one responded invite
foreach($invitations as $i) {
	if($i->getResponse() !== null) {
		$fork->set('extraButton', true);
		$fork->set('extraButtonLabel', 'Show Responded');		
		break;
	}
}
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){

	$('#invitations .extraButton').click(function() {
		$(this).hide();
		$('#invitations li.none').hide();
		var responded = $("#invitations li.responded");
		$(responded).fadeIn();
	});		

	$('#invitations div.buttons input[type="button"]').click(function(){
		var id = $(this).parent().parent().attr('id').substring(11);
		buildPost({
			'processPage': '<?= Url::dashboardProcess() ?>',
			'info':{
				'inviteID': id,
				'response': $(this).attr('class')
			},
			'buttonID':$(this)
		});
	});
	
});

</script>

<ul class="segmented-list invitations">

<?php

if(empty($unrespondedInvites)) {
	echo '<li class="none">(none)</li>';
} 

foreach($invitations as $i) {
	// project title
	$project = Project::load($i->getProjectID());
	$projectTitle = $project->getTitle();
	
	if($i->getResponse() != null) {
		echo '<li id="invitation-'.$i->getID().'" class="responded hidden">';
	} else {
		echo '<li id="invitation-'.$i->getID().'">';
	}

	if($i->getTrusted()) {
		echo '<p class="project">'.formatUserLink($i->getInviterID(), $project->getID()).' invited you to join the project '.formatProjectLink($i->getProjectID()).' as a <a href="'.Url::help().'">trusted member</a>. ('.formatTimeTag($i->getDateCreated()).')</p>';
	} else {
		echo '<p class="project">'.formatUserLink($i->getInviterID(), $project->getID()).' invited you to join the project '.formatProjectLink($i->getProjectID()).'. ('.formatTimeTag($i->getDateCreated()).')</p>';
	}
	
	// show the invitation message, if it exists
	if($i->getInvitationMessage() != null) {
		echo '<blockquote>'.formatInvitationMessage($i->getInvitationMessage()).'</blockquote>';
	}		
	
	// only show response buttons if user hasn't responded yet
	if($i->getResponse() === null) {
		echo '<div class="buttons">';
		// don't allow accept invitation if already affiliated
		if(!$project->isAffiliated($i->getInviteeID())) {
			echo '<input class="accept" type="button" value="Accept" /> ';
		}
		echo '<input class="decline" type="button" value="Decline" /></div>';
	} else {
		//echo '<div class="line"></div>';
		// show the response
		if($i->getResponse() == Invitation::ACCEPTED) {
			echo '<p>You accepted this invitation. ('.formatTimeTag($i->getDateResponded()).')</p>';					
		} else {
			echo '<p>You declined this invitation. ('.formatTimeTag($i->getDateResponded()).')</p>';		
		}
		// show the response message, if it exists
		if($i->getResponseMessage() != null) {
			echo '<blockquote>'.formatInvitationMessage($i->getResponseMessage()).'</blockquote>';
		}			
	}
			
	echo '</li>';
}

?>

</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');