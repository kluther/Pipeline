<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$hasTrustPermission = Session::isAdmin() ||
		$project->isTrusted(Session::getUserID()) ||
		$project->isCreator(Session::getUserID());

$fork = $SOUP->fork();
$fork->set('title', 'Invite Members');
//$fork->set('id', 'invite');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){

	$('#txtInviteMembers').focus();

	$('#btnInviteMembers').click(function() {
		var trusted = ($('#chkTrustedMember').is(':checked')) ? 1 : 0;
		buildPost({
			'processPage': '<?= Url::peopleInviteProcess($project->getID()) ?>',
			'info': {
				'invitees': $('#txtInviteMembers').val(),
				'trusted': trusted,
				'message': $('#txtInviteMembersMessage').val()
			},
			'buttonID': '#btnInviteMembers'
		});
	});	

	$('#btnCancelMembers').click(function() {
		window.location = '<?= Url::details($project->getID()) ?>';
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

});

</script>

<p><strong>Your project was created!</strong> Now you need a team.</p>
<p>Use the form below to invite people to join your shiny new project. Or, you can manually share the <a href="<?= Url::project($project->getID()) ?>">project link</a> with friends via email, social media, etc.</p>

<div class="line"> </div>

<div class="clear">
	<label for="txtInviteMembers">People to Invite</label>
	<div class="input">
		<textarea id="txtInviteMembers" name="txtInviteMembers" style="height: 75px;"></textarea>
		<p>Email addresses and/or <?= PIPELINE_NAME ?> usernames, separated by commas (,)</p>
	</div>
</div>
<?php if($hasTrustPermission): ?>
<div class="clear">
	<label for="chkTrustedMember">Trusted</label>
	<div class="input">
		<input type="checkbox" id="chkTrustedMember" value="trusted" checked="checked" />
		<p>If checked, recipients who accept this invitation become <a href="<?= Url::help() ?>#help-roles">trusted members</a></p>
	</div>
</div>
<?php endif; ?>
<div class="clear">
	<label for="txtInviteMembersMessage">Message</label>
	<div class="input">
		<textarea id="txtInviteMembersMessage">Please join my project! It will knock your socks off.</textarea>
		<p>Why the recipients should join this project; <a class="help-link" href="<?= Url::help() ?>#help-html-allowed">some HTML allowed</a></p>
	</div>
</div>	
<div class="clear">
	<div class="input">
		<input type="button" id="btnInviteMembers" value="Invite Members" />
		<input type="button" id="btnCancelMembers" value="Skip This Step" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
