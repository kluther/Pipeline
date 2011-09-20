<?phpinclude_once TEMPLATE_PATH.'/site/helper/format.php';$project = $SOUP->get('project');$organizers = $SOUP->get('organizers');$organizerInvites = $SOUP->get('organizerInvites');// only organizers or creator may edit/invite organizers$hasPermission = ( Session::isAdmin() ||					ProjectUser::isOrganizer(Session::getUserID(), $project->getID()) ||					ProjectUser::isCreator(Session::getUserID(), $project->getID()) );$fork = $SOUP->fork();$fork->set('id', 'organizers');$fork->set('title', 'Organizers');$fork->set('creatable', $hasPermission);$fork->set('createLabel', 'Invite Organizers');$fork->set('editable', $hasPermission);$fork->set('editLabel', 'Edit Organizers');$fork->set('extraButton', $hasPermission);$fork->set('extraButtonLabel', 'Show Invited');$fork->startBlockSet('body');?><?php if($hasPermission): ?><script type="text/javascript">$(document).ready(function(){	$('#organizers .extraButton').click(function() {		var invited = $("#organizers li.invited");		if($(invited).is(":hidden")) {			$('#organizers li.none').hide();			$(invited).fadeIn();		} else {			$(invited).hide();			$('#organizers li.none').show();					}	});			$('#organizers div.invite-box').dialog({		autoOpen: false,		title: 'View Organizer Invitation',		modal: true,		width: 500	});		$('#organizers .viewInvite').click(function(){		var id = $(this).attr('id').substring(11);		$('#invite-box-'+id).dialog('open');	});	$("#organizers .editButton").click(function(){		var revoke = $("#organizers input.revoke");		if($(revoke).is(":hidden")) {			$(revoke).fadeIn();		} else {			$(revoke).fadeOut();		}	});		$("#organizers .createButton").click(function(){		var invite = $("#organizers .invite");		var view = $("#organizers .view");		toggleEditView(view, invite);		if($(view).is(":hidden"))			$('#txtInviteOrganizers').focus();	});		$("#btnCancelOrganizers").click(function(){		$("#organizers .invite").hide();		$("#organizers .view").fadeIn();	});		$("#organizers input.revoke").click(function(){		var id = $(this).attr('id').substring(7);		buildPost({			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',			'info': {				'action': 'revoke-organizer',				'userID': id			},			'buttonID': $(this)		});	});$( "#txtInviteOrganizers" )	// don't navigate away from the field on tab when selecting an item	.bind( "keydown", function( event ) {		if ( event.keyCode === $.ui.keyCode.TAB &&				$( this ).data( "autocomplete" ).menu.active ) {			event.preventDefault();		}	})	.autocomplete({		source: function( request, response ) {			$.getJSON( '<?= Url::peopleSearch($project->getID()) ?>/not-affiliated', {				term: extractLast( request.term )			}, response );		},		search: function() {			// custom minLength			var term = extractLast( this.value );			if ( term.length < 2 ) {				return false;			}		},		focus: function() {			// prevent value inserted on focus			return false;		},		select: function( event, ui ) {			var terms = split( this.value );			// remove the current input			terms.pop();			// add the selected item			terms.push( ui.item.value );			// add placeholder to get the comma-and-space at the end			terms.push( "" );			this.value = terms.join( ", " );			return false;		}	});			$('#btnInviteOrganizers').click(function() {		buildPost({			'processPage': '<?= Url::peopleProcess($project->getID()) ?>',			'info': {				'action': 'invite-organizers',				'invitees': $('#txtInviteOrganizers').val(),				'message': $('#txtInviteOrganizersMessage').val()			},			'buttonID': '#btnInviteOrganizers'		});	});	});</script><div class="invite hidden">	<div class="clear">		<label for="txtInviteOrganizers">People to Invite</label>		<div class="input">			<input type="text" id="txtInviteOrganizers" />			<p>Usernames or email addresses, separated by commas</p>		</div>	</div>	<div class="clear">		<label for="txtInviteOrganizersMessage">Message</label>		<div class="input">			<textarea id="txtInviteOrganizersMessage"></textarea>			<p>Why the recipient(s) should help organize this project</p>		</div>	</div>			<div class="clear">		<div class="input">			<input type="button" id="btnInviteOrganizers" value="Invite" />			<input type="button" id="btnCancelOrganizers" value="Cancel" />		</div>	</div></div><?php endif; ?><div class="view"><ul class="segmented-list users"><?php // organizersif(empty($organizers)) {	echo '<li class="none">(none)</li>';} else {	foreach($organizers as $o) {		echo '<li>';		if($hasPermission)			echo '<input id="revoke-'.$o->getID().'" type="button" class="revoke hidden" value="Revoke Organizer" />';		echo formatUserPicture($o->getID(), 'small');		echo '<h6 class="primary">'.formatUserLink($o->getID()).'</h6>';		echo '<p class="secondary">organizer</p>';		echo '</li>';	}	//echo '</ul>';} // organizer invitesif(!empty($organizerInvites)) {	foreach($organizerInvites as $oi) {		// don't list accepted invites		if($oi->getResponse() == Invitation::ACCEPTED) {			continue;		}				echo '<li class="invited">';		// View Invitation button		echo '<input id="invitation-'.$oi->getID().'" type="button" class="viewInvite" value="View Invitation" />';		// invite box		echo '<div id="invite-box-'.$oi->getID().'" class="invite-box hidden">';		echo '<p>invitation sent '.formatTimeTag($oi->getDateCreated()).' by '.formatUserLink($oi->getInviterID()).'</p>';		if($oi->getInvitationMessage() != null)			echo '<blockquote>'.formatInvitationMessage($oi->getInvitationMessage()).'</blockquote>';		if($oi->getResponse() == Invitation::DECLINED) {			echo '<p><span class="bad">declined</span> '.formatTimeTag($oi->getDateResponded()).'</p>';			if($oi->getResponseMessage() != null)				echo '<blockquote>'.formatInvitationMessage($oi->getResponseMessage()).'</blockquote>';		} else {			echo '<p>(no response yet)</p>';		}		echo '</div>';				// invitee picture and username		if($oi->getInviteeID() != null) {			echo formatUserPicture($oi->getInviteeID(), 'small');			echo '<h6 class="primary">'.formatUserLink($oi->getInviteeID()).'</h6>';		} else {			echo '<h6 class="primary"><a href="mailto:'.$oi->getInviteeEmail().'">'.$oi->getInviteeEmail().'</a></h6>';		}		// response		if($oi->getResponse() == Invitation::DECLINED) {			echo '<p class="secondary"><span class="bad">declined</span></p>';		} else {			echo '<p class="secondary">invited</p>';		}		echo '</li>';	}}?></ul></div><?php$fork->endBlockSet();$fork->render('site/partial/panel');