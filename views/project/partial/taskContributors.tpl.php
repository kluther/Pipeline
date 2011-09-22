<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$task = $SOUP->get('task');
$joined = $SOUP->get('accepted');
$contributorInvites = $SOUP->get('contributorInvites');

// num joined
$numJoined = $task->getNumAccepted();
$numJoined = formatCount($numJoined,'person','people');

// num needed
$numNeeded = $task->getNumNeeded();
if($numNeeded == 0)
	$numNeeded = '&#8734; people';
else {
	$numNeeded -= $numJoined;
	$numNeeded = formatCount($numNeeded,'person','people');
}

// can user join task or contribute to task?

$hasJoinPermission = false;
$hasContributePermission = false;
if(Session::isLoggedIn() && // must be logged in
	(!ProjectUser::isBanned(Session::getUserID(), $task->getProjectID())) && // can't be banned
	($task->getStatus() == Task::STATUS_OPEN) ) { // task must be open
	// now determine between "Join" and "Contribute"
	if(Accepted::getByUserID(Session::getUserID(), $task->getID()) != null) {
		// user has already joined
		$hasContributePermission = true;
	} else {
		// user hasn't joined yet
		$hasJoinPermission = true;
	}
}

// can user invite contributors?

$hasInvitePermission = ( Session::isAdmin() ||
					ProjectUser::isOrganizer(Session::getUserID(), $task->getProjectID()) ||
					ProjectUser::isCreator(Session::getUserID(), $task->getProjectID()) ||
					ProjectUser::isContributor(Session::getUserID(), $task->getProjectID()) );

$fork = $SOUP->fork();
$fork->set('title', 'Contributors');
$fork->set('id', 'contributors');
if($hasJoinPermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Join');
} elseif($hasContributePermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Contribute');
}
$fork->set('extraButton', $hasInvitePermission);
$fork->set('extraButtonLabel', 'Invite');
$fork->set('extraButton2', $hasInvitePermission);
$fork->set('extraButton2Label', 'Show Invited');

$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function() {

<?php if($hasJoinPermission): ?>

	var btnJoin = $('#contributors .createButton');
	btnJoin.click(function() {
		buildPost({
			'processPage': '<?= Url::taskProcess($task->getID()) ?>',
			'info':{
				'action': 'accept'
			},
			'buttonID': btnJoin
		});
	});
	
<?php elseif($hasContributePermission): ?>

	$('#contributors .createButton').click(function(){
		window.location = '<?= Url::updateNew($task->getID()) ?>';
	});

<?php endif; ?>

<?php if($hasInvitePermission): ?>

	$('#contributors .extraButton2').click(function() {
		var invited = $("#contributors li.invited");
		if($(invited).is(":hidden")) {
			$(invited).fadeIn();
		} else {
			$(invited).hide();
		}
	});	
	
	$('#contributors div.invite-box').dialog({
		autoOpen: false,
		title: 'View Contributor Invitation',
		modal: true,
		width: 500
	});
	
	$('#contributors .viewInvite').click(function(){
		var id = $(this).attr('id').substring(11);
		$('#invite-box-'+id).dialog('open');
	});	

	$("#contributors .extraButton").click(function(){
		var invite = $("#contributors .invite");
		var view = $("#contributors .view");
		toggleEditView(view, invite);
		if($(view).is(":hidden"))
			$('#txtInviteContributors').focus();
	});
	
	$("#btnCancelContributors").click(function(){
		$("#contributors .invite").hide();
		$("#contributors .view").fadeIn();
	});	

	$( "#txtInviteContributors" )
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			source: function( request, response ) {
				$.getJSON( '<?= Url::peopleSearch($task->getProjectID()) ?>/possible-contributors', {
					term: extractLast( request.term )
				}, response );
			},
			search: function() {
				// custom minLength
				var term = extractLast( this.value );
				if ( term.length < 2 ) {
					return false;
				}
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
		});		
		
	$('#btnInviteContributors').click(function() {
		buildPost({
			'processPage': '<?= Url::peopleProcess($task->getProjectID()) ?>',
			'info': {
				'action': 'invite-contributors',
				'invitees': $('#txtInviteContributors').val(),
				'taskID': '<?= $task->getID() ?>',
				'message': $('#txtInviteContributorsMessage').val()
			},
			'buttonID': '#btnInviteContributors'
		});
	});	

<?php endif; ?>
	
});

</script>

<div class="view">

<p><?= $numNeeded ?> needed <span class="slash">/</span> <?= $numJoined ?> joined</p>

<?php

if( !empty($joined) || !empty($contributorInvites) ) {
	echo '<div class="line"></div>';
	echo '<ul class="segmented-list users">';
}

// contributors

if($joined != null) {
	foreach($joined as $j) {
		echo '<li>';
		echo formatUserPicture($j->getCreatorID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($j->getCreatorID()).'</h6>';
		$latestUpdate = $j->getLatestUpdate();
		if($latestUpdate != null) {
			echo '<p class="secondary contribution"><a href="'.Url::update($latestUpdate->getID()).'">last contributed '.formatTimeTag($latestUpdate->getDateCreated()).'</a></p>';
		//	echo '<h6 class="primary contribution"><a href="'.Url::update($latestUpdate->getID()).'"><strong>'.$latestUpdate->getTitle().'</strong></a></h6>';
		//	echo '<h6 class="primary contribution">></h6>';
		} else {
			echo '<p class="secondary">joined '.formatTimeTag($j->getDateCreated()).'</p>';
		}
		echo '</li>';
	}
}

// invited contributors

if(!empty($contributorInvites)) {
	foreach($contributorInvites as $ci) {
		// don't list accepted invites
		if($ci->getResponse() == Invitation::ACCEPTED) {
			continue;
		}
		
		$inviteeLink = ($ci->getInviteeID() != null) ? formatUserLink($ci->getInviteeID()) : '<a href="mailto:'.$ci->getInviteeEmail().'">'.$ci->getInviteeEmail().'</a>';
		
		echo '<li class="invited">';
		// View Invitation button
		echo '<input id="invitation-'.$ci->getID().'" type="button" class="viewInvite" value="View Invitation" />';
		// invite box
		echo '<div id="invite-box-'.$ci->getID().'" class="invite-box hidden">';
		// inviter message
		//$task = Task::load($ci->getTaskID());
		echo '<p>'.formatUserLink($ci->getInviterID()).' invited '.$inviteeLink.' to contribute to the task <a href="'.Url::task($task->getID()).'">'.formatTitle($task->getTitle()).'</a>. ('.formatTimeTag($ci->getDateCreated()).')</p>';
		if($ci->getInvitationMessage() != null)
			echo '<blockquote>'.formatInvitationMessage($ci->getInvitationMessage()).'</blockquote>';
		// invitee response
		echo '<div class="line"></div>';
		if($ci->getResponse() == Invitation::DECLINED) {
			echo '<p>'.$inviteeLink.' <span class="bad">declined</span> the invitation. ('.formatTimeTag($ci->getDateResponded()).')</p>';
			if($ci->getResponseMessage() != null)
				echo '<blockquote>'.formatInvitationMessage($ci->getResponseMessage()).'</blockquote>';
		} else {
			echo '<p>(no response yet)</p>';
		}
		echo '</div>';		
		// invitee picture and username
		if($ci->getInviteeID() != null) {
			echo formatUserPicture($ci->getInviteeID(), 'small');
		} 
		echo '<h6 class="primary">'.$inviteeLink.'</h6>';
		
		// response
		if($ci->getResponse() == Invitation::DECLINED) {
			echo '<p class="secondary"><span class="bad">declined</span></p>';
		} else {
			echo '<p class="secondary">invited</p>';
		}
		echo '</li>';
	}
}

if( !empty($joined) || !empty($contributorInvites) ) {
	echo '</ul>';
}

?>

</div>

<?php if($hasInvitePermission): ?>

<div class="invite hidden">
	<div class="clear">
		<label for="txtInviteContributors">People to Invite<span class="required">*</span></label>
		<div class="input">
			<input type="text" id="txtInviteContributors" />
			<p>Usernames or email addresses, separated by commas</p>
		</div>
	</div>
	<div class="clear">
		<label for="txtInviteContributorsMessage">Message</label>
		<div class="input">
			<textarea id="txtInviteContributorsMessage"></textarea>
			<p>Why the recipient(s) should contribute to this task</p>
		</div>
	</div>	
	<div class="clear">
		<div class="input">
			<input type="button" id="btnInviteContributors" value="Invite" />
			<input type="button" id="btnCancelContributors" value="Cancel" />
		</div>
	</div>
</div>

<?php endif; ?>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');