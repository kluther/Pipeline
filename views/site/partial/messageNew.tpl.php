<?php

$username = $SOUP->get('username','');

$fork = $SOUP->fork();
$fork->set('title', 'Compose Message');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	<?php if(!empty($username)): ?>
	$('#txtSubject').focus();
	<?php else: ?>
	$('#txtRecipient').focus();
	<?php endif; ?>
	$('#txtRecipient').autocomplete({
		source: '<?= Url::userSearch('not-me') ?>',
		minLength: 2
	});		
	$('#btnSendMessage').click(function(){
		buildPost({
			'processPage':'<?= Url::inboxProcess() ?>',
			'info':{
				'action':'send',
				'recipient':$('#txtRecipient').val(),
				'subject':$('#txtSubject').val(),
				'body':$('#txtBody').val()
			},
			'buttonID':'#btnSendMessage'
		});
	});	
});

</script>

<div class="clear">
	<label for="txtRecipient">Recipient<span class="required">*</span></label>
	<div class="input">
		<input id="txtRecipient" type="text" maxlength="255" value="<?= $username ?>" />
		<p>Must be a username</p>
	</div>
</div>

<div class="clear">
	<label for="txtSubject">Subject<span class="required">*</span></label>
	<div class="input">
		<input id="txtSubject" type="text" maxlength="255" />
	</div>
</div>

<div class="clear">
	<label for="txtBody">Body<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtBody"></textarea>
		<p><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnSendMessage" type="button" value="Send Message" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');