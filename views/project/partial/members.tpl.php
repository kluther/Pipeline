<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$allMembers = $SOUP->get('allMembers');
$memberInvites = $SOUP->get('memberInvites');

// admin, contributor, trusted, creator may invite
$hasInvitePermission = ( Session::isAdmin() ||
						$project->isMember(Session::getUserID()) ||
						$project->isTrusted(Session::getUserID()) || 
						$project->isCreator(Session::getUserID()) );

// admin, trusted, creator may edit
$hasEditPermission = ( Session::isAdmin() ||
						$project->isTrusted(Session::getUserID()) || 
						$project->isCreator(Session::getUserID()) );

$fork = $SOUP->fork();
$fork->set('title', 'Members');
$fork->set('id', 'members');
$fork->set('creatable', $hasInvitePermission);
$fork->set('createLabel', 'Invite Members');
// only show Show Invites button if has permission AND there are invites to view
if($hasInvitePermission && !empty($memberInvites)) {
	$fork->set('extraButton', true);
	$fork->set('extraButtonLabel', 'Show Invited');
}
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){

<?php if($hasInvitePermission): ?>

	$("#members .createButton").click(function(){
		$(this).hide();
		$("#members .view").hide();
		$("#members .invite").fadeIn();
		$('#txtInviteMembers').focus();
	});
	
	$("#btnCancelMembers").click(function(){
		$("#members .invite").hide();
		$("#members .view").fadeIn();
		$("#members .createButton").fadeIn();
	});	
	
	$('#btnInviteMembers').click(function() {
		var trusted = ($('#chkTrustedMember').is(':checked')) ? 1 : 0;
		buildPost({
			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',
			'info': {
				'action': 'invite-members',
				'invitees': $('#txtInviteMembers').val(),
				'trusted': trusted,
				'message': $('#txtInviteMembersMessage').val()
			},
			'buttonID': '#btnInviteMembers'
		});
	});		
	
	$( "#txtInviteMembers" )
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			source: function( request, response ) {
				$.getJSON( '<?= Url::peopleSearch($project->getID()) ?>/unaffiliated', {
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
	
	$('#members .extraButton').click(function() {
		$(this).hide();
		// hide the view, if it's open
		if($("#members .view").is(":hidden")) {
			$("#btnCancelMembers").click();
		}	
		var invited = $("#members li.invited");
		$(invited).fadeIn();
	});	
	
	$('#members div.invite-box').dialog({
		autoOpen: false,
		title: 'View Member Invitation',
		modal: true,
		width: 500
	});
	
	$('#members .viewInvite').click(function(){
		var id = $(this).attr('id').substring(11);
		$('#invite-box-'+id).dialog('open');
	});	


<?php endif; ?>

<?php if($hasEditPermission): ?>
	
	$("#members input.ban").click(function(){
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
	
	$("#members input.trust").click(function(){
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
	
	$("#members input.untrust").click(function(){
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
	
<?php endif; ?>

});

</script>

<?php if($hasInvitePermission): ?>

<div class="invite hidden">
	<div class="clear">
		<label for="txtInviteMembers">People to Invite<span class="required">*</span></label>
		<div class="input">
			<input type="text" id="txtInviteMembers" />
			<p>Usernames and/or email addresses, separated by commas</p>
		</div>
	</div>
	<?php if($hasEditPermission): ?>
	<div class="clear">
		<label for="chkTrustedMember">Trusted<span class="required">*</span></label>
		<div class="input">
			<input type="checkbox" id="chkTrustedMember" value="trusted" checked="checked" />
			<p>If checked, recipients who accept this invitation become <a href="<?= Url::help() ?>">trusted members</a></p>
		</div>
	</div>
	<?php endif; ?>
	<div class="clear">
		<label for="txtInviteMembersMessage">Message</label>
		<div class="input">
			<textarea id="txtInviteMembersMessage"></textarea>
			<p>Why the recipients should join this project; <a class="help-link" href="<?= Url::help() ?>#help-html-allowed">some HTML allowed</a></p>
		</div>
	</div>	
	<div class="clear">
		<div class="input">
			<input type="button" id="btnInviteMembers" value="Invite" />
			<input type="button" id="btnCancelMembers" value="Cancel" />
		</div>
	</div>
</div>

<?php endif; ?>

<div class="view">

<ul class="segmented-list users">

	<li>
		<?= formatUserPicture($project->getCreatorID(), 'small') ?>
		<h6 class="primary"><?= formatUserLink($project->getCreatorID(), $project->getID()) ?></h6>
		<p class="secondary">creator</p>
	</li>

<?php

// never empty because there is always a creator
foreach($allMembers as $m) {
	echo '<li>';
	if($project->isTrusted($m->getID())) {
		// trusted member
		if($hasEditPermission) {
			echo '<input id="ban-'.$m->getID().'" type="button" class="ban" value="Ban" /> <input id="untrust-'.$m->getID().'" type="button" class="untrust" value="Untrust" />';
		}
		echo formatUserPicture($m->getID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($m->getID(), $project->getID()).'</h6>';
		echo '<p class="secondary">trusted member</p>';				
	} else {
		// member
		if($hasEditPermission) {
			echo '<input id="ban-'.$m->getID().'" type="button" class="ban" value="Ban" /> <input id="trust-'.$m->getID().'" type="button" class="trust" value="Trust" />';
		}
		echo formatUserPicture($m->getID(), 'small');
		echo '<h6 class="primary">'.formatUserLink($m->getID(), $project->getID()).'</h6>';
		echo '<p class="secondary">member</p>';					
	}
	echo '</li>';
}

// member invites
if($hasInvitePermission && !empty($memberInvites)) {
	foreach($memberInvites as $mi) {
		// don't list accepted invites
		if($mi->getResponse() == Invitation::ACCEPTED) {
			continue;
		}
		
		$inviterLink = formatUserLink($mi->getInviterID(), $project->getID());
		$inviteeLink = ($mi->getInviteeID() != null) ? formatUserLink($mi->getInviteeID(), $project->getID()) : '<a href="mailto:'.$mi->getInviteeEmail().'">'.$mi->getInviteeEmail().'</a>';
		
		echo '<li class="invited">';
		// View Invitation button
		echo '<input id="invitation-'.$mi->getID().'" type="button" class="viewInvite" value="View Invitation" />';
		// invite box
		echo '<div id="invite-box-'.$mi->getID().'" class="invite-box hidden">';
		if($mi->getTrusted()) {
			echo '<p>'.$inviterLink.' invited '.$inviteeLink.' to join this project as a <a href="'.Url::help().'">trusted member</a>. ('.formatTimeTag($mi->getDateCreated()).')</p>';
		} else {
			echo '<p>'.$inviterLink.' invited '.$inviteeLink.' to join this project. ('.formatTimeTag($mi->getDateCreated()).')</p>';
		}
		if($mi->getInvitationMessage() != null)
			echo '<blockquote>'.formatInvitationMessage($mi->getInvitationMessage()).'</blockquote>';
		echo '<div class="line"></div>';
		if($mi->getResponse() == Invitation::DECLINED) {
			echo '<p>'.$inviteeLink.' declined the invitation. ('.formatTimeTag($mi->getDateResponded()).')</p>';
			if($mi->getResponseMessage() != null)
				echo '<blockquote>'.formatInvitationMessage($mi->getResponseMessage()).'</blockquote>';
		} else {
			echo '<p>(no response yet)</p>';
		}
		echo '</div>';		
		// invitee picture and username
		if($mi->getInviteeID() != null) {
			echo formatUserPicture($mi->getInviteeID(), 'small');
		} else {
			echo formatBlankUserPicture('mailto:'.$mi->getInviteeEmail(), 'small');
		}
		echo '<h6 class="primary">'.$inviteeLink.'</h6>';
		
		// response
		if($mi->getResponse() == Invitation::DECLINED) {
			echo '<p class="secondary">declined</p>';
		} else {
			echo '<p class="secondary">invited</p>';
		}
		echo '</li>';
	}
}

?>

</ul>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');