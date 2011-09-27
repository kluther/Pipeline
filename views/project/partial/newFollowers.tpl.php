<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$trustedFollowers = $SOUP->get('trustedFollowers');
$untrustedFollowers = $SOUP->get('untrustedFollowers');
$followerInvites = $SOUP->get('followerInvites');

// admin, contributor, or trusted may invite
$hasInvitePermission = ( Session::isAdmin() ||
						$project->isContributor(Session::getUserID()) ||
						$project->isTrusted(Session::getUserID()) );

// admin or trusted may edit
$hasEditPermission = ( Session::isAdmin() ||
						$project->isTrusted(Session::getUserID()) );

$fork = $SOUP->fork();
$fork->set('title', 'Followers');
$fork->set('id', 'followers');
$fork->set('creatable', $hasInvitePermission);
$fork->set('createLabel', 'Invite Followers');
// only show Show Invites button if has permission AND there are invites to view
if($hasInvitePermission && !empty($followerInvites)) {
	$fork->set('extraButton', true);
	$fork->set('extraButtonLabel', 'Show Invited');
}
// only show Edit button if has permission AND there are followers to edit
// if($hasEditPermission &&
	// (!empty($trustedFollowers) || !empty($untrustedFollowers)) ) {
	// $fork->set('editable', true);
	// $fork->set('editLabel', 'Edit Followers');
// }
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){

<?php if($hasInvitePermission): ?>

	$("#followers .createButton").click(function(){
		$(this).hide();
		$("#followers .view").hide();
		$("#followers .invite").fadeIn();
		$('#txtInviteFollowers').focus();
	});
	
	$("#btnCancelFollowers").click(function(){
		$("#followers .invite").hide();
		$("#followers .view").fadeIn();
		$("#followers .createButton").fadeIn();
	});	
	
	$('#btnInviteFollowers').click(function() {
		var trusted = ($('#chkTrustedFollower').is(':checked')) ? 1 : 0;
		buildPost({
			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',
			'info': {
				'action': 'invite-followers',
				'invitees': $('#txtInviteFollowers').val(),
				'trusted': trusted,
				'message': $('#txtInviteFollowersMessage').val()
			},
			'buttonID': '#btnInviteFollowers'
		});
	});		
	
	$( "#txtInviteFollowers" )
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
	
	$('#followers .extraButton').click(function() {
		// hide the view, if it's open
		if($("#followers .view").is(":hidden")) {
			$("#btnCancelFollowers").click();
		}	
		
		var invited = $("#followers li.invited");
		if($(invited).is(":hidden")) {
			$('#followers li.none').hide();
			$(invited).fadeIn();
		} else {
			$(invited).hide();
			$('#followers li.none').show();
		}
	});	
	
	$('#followers div.invite-box').dialog({
		autoOpen: false,
		title: 'View Follower Invitation',
		modal: true,
		width: 500
	});
	
	$('#followers .viewInvite').click(function(){
		var id = $(this).attr('id').substring(11);
		$('#invite-box-'+id).dialog('open');
	});	


<?php endif; ?>

<?php if($hasEditPermission): ?>

	// $("#followers .editButton").click(function(){
		// // hide the view, if it's open
		// if($("#followers .view").is(":hidden")) {
			// $("#btnCancelFollowers").click();
		// }
		// // toggle button visibility
		// var buttons = $("#followers li.follower input[type='button']");
		// if($(buttons).is(":hidden")) {
			// $(buttons).fadeIn();
		// } else {
			// $(buttons).hide();
		// }
	// });
	
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
	
	$("#followers input.trust").click(function(){
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
	
	$("#followers input.untrust").click(function(){
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
		<label for="txtInviteFollowers">People to Invite<span class="required">*</span></label>
		<div class="input">
			<input type="text" id="txtInviteFollowers" />
			<p>Usernames or email addresses, separated by commas</p>
		</div>
	</div>
	<?php if($hasEditPermission): ?>
	<div class="clear">
		<label for="chkTrustedFollower">Trusted<span class="required">*</span></label>
		<div class="input">
			<input type="checkbox" id="chkTrustedFollower" value="trusted" checked="checked" />
			<p>Recipients who accept this invitation become <a href="<?= Url::help() ?>">trusted users</a></p>
		</div>
	</div>
	<?php endif; ?>
	<div class="clear">
		<label for="txtInviteFollowersMessage">Message</label>
		<div class="input">
			<textarea id="txtInviteFollowersMessage"></textarea>
			<p>Why the recipient(s) should follow this project</p>
		</div>
	</div>	
	<div class="clear">
		<div class="input">
			<input type="button" id="btnInviteFollowers" value="Invite" />
			<input type="button" id="btnCancelFollowers" value="Cancel" />
		</div>
	</div>
</div>

<?php endif; ?>

<div class="view">

<ul class="segmented-list users">

<?php

if(empty($trustedFollowers) && (empty($untrustedFollowers))) {
	echo '<li class="none">(none)</li>';
} else {
	// trusted followers
	if(!empty($trustedFollowers)) {
		foreach($trustedFollowers as $tf) {
			echo '<li class="trusted follower">';
			if($hasEditPermission) {
				echo '<input id="ban-'.$tf->getID().'" type="button" class="ban" value="Ban" /> <input id="untrust-'.$tf->getID().'" type="button" class="untrust" value="Untrust" />';
			}
			echo formatUserPicture($tf->getID(), 'small');
			echo '<h6 class="primary">'.formatUserLink($tf->getID()).'*</h6>';
			echo '<p class="secondary">follower</p>';
			echo '</li>';
		}
	}
	// untrusted followers
	if(!empty($untrustedFollowers)) {
		foreach($untrustedFollowers as $uf) {
			echo '<li class="untrusted follower">';
			if($hasEditPermission) {
				echo '<input id="ban-'.$uf->getID().'" type="button" class="ban" value="Ban" /> <input id="trust-'.$uf->getID().'" type="button" class="trust" value="Trust" />';
			}
			echo formatUserPicture($uf->getID(), 'small');
			echo '<h6 class="primary">'.formatUserLink($uf->getID()).'</h6>';
			echo '<p class="secondary">follower</p>';
			echo '</li>';
		}
	}
}

// follower invites
if($hasInvitePermission && !empty($followerInvites)) {
	foreach($followerInvites as $fi) {
		// don't list accepted invites
		if($fi->getResponse() == Invitation::ACCEPTED) {
			continue;
		}
		
		$inviteeLink = ($fi->getInviteeID() != null) ? formatUserLink($fi->getInviteeID()) : '<a href="mailto:'.$fi->getInviteeEmail().'">'.$fi->getInviteeEmail().'</a>';
		
		echo '<li class="invited">';
		// View Invitation button
		echo '<input id="invitation-'.$fi->getID().'" type="button" class="viewInvite" value="View Invitation" />';
		// invite box
		echo '<div id="invite-box-'.$fi->getID().'" class="invite-box hidden">';
		echo '<p>'.formatUserLink($fi->getInviterID()).' invited '.$inviteeLink.' to follow this project. ('.formatTimeTag($fi->getDateCreated()).')</p>';
		if($fi->getInvitationMessage() != null)
			echo '<blockquote>'.formatInvitationMessage($fi->getInvitationMessage()).'</blockquote>';
		echo '<div class="line"></div>';
		if($fi->getResponse() == Invitation::DECLINED) {
			echo '<p>'.$inviteeLink.' <span class="bad">declined</span> the invitation. ('.formatTimeTag($fi->getDateResponded()).')</p>';
			if($fi->getResponseMessage() != null)
				echo '<blockquote>'.formatInvitationMessage($fi->getResponseMessage()).'</blockquote>';
		} else {
			echo '<p>(no response yet)</p>';
		}
		echo '</div>';		
		// invitee picture and username
		if($fi->getInviteeID() != null) {
			echo formatUserPicture($fi->getInviteeID(), 'small');
		}
		echo '<h6 class="primary">'.$inviteeLink.'</h6>';
		
		// response
		if($fi->getResponse() == Invitation::DECLINED) {
			echo '<p class="secondary"><span class="bad">declined</span></p>';
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
$fork->startBlockSet('footer');

?>

<p>A star (*) indicates a <a href="<?= Url::help() ?>">trusted user</a>.</p>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');