<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$massEmailAddresses = User::getMassEmailAddresses();
$numMassEmails = formatCount(count($massEmailAddresses),'user','users');

$fork = $SOUP->fork();
$fork->set('title', 'Mass Email');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtSubject').focus();
	$('#btnSendEmail').click(function(){
		buildPost({
			'processPage': '<?= Url::adminProcess() ?>',
			'info':{
				'subject': $('#txtSubject').val(),
				'body': $('#txtBody').val()
			},
			'buttonID': '#btnSendEmail'
		});
	});
});

</script>

<p>This email will be sent to <strong><?= $numMassEmails ?></strong>.</p>
<p>Due to Gmail restrictions, mass emails are limited to 500 users or less.</p>
<p>Users who have opted out of mass emails will not receive this email.</p>

<div class="line"> </div>

<div class="clear">
	<label for="txtFrom">From<span class="required">*</span></label>
	<div class="input">
		<input type="text" id="txtFrom" name="txtFrom" value="<?= SMTP_FROM_EMAIL ?>" readonly="readonly" />
		<p>Can be changed in config.php</p>
	</div>
</div>

<div class="clear">
	<label for="txtSubject">Subject<span class="required">*</span></label>
	<div class="input">
		<input type="text" id="txtSubject" name="txtSubject" />
	</div>
</div>

<div class="clear">
	<label for="txtBody">Body<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtBody" name="txtBody"></textarea>
		<p><a href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input type="button" id="btnSendEmail" value="Send Mass Email" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');