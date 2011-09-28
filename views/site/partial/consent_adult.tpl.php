<?php
$email = $SOUP->get('email');

$fork = $SOUP->fork();
$fork->startBlockSet('body');

?>
<script type="text/javascript">

$(document).ready(function(){
	$('#txtConsentEmail').focus();
	
	$('#btnAgree').mousedown(function(){	
		buildPost({
			'processPage':'<?= Url::consentProcess() ?>',
			'info':{
				'email': $('#txtConsentEmail').val(),
				'name': $('#txtConsentName').val()
			},
			'buttonID':'#btnAgree'
		});
	});
	
	$('#btnNoThanks').mousedown(function(){
		window.location = '<?= Url::base() ?>';
	});
	
});

</script>

<p>Please read the following consent form and then complete the form below it.</p>

<a title="View Adult Web Consent Testing on Scribd" href="http://www.scribd.com/doc/66688220/Adult-Web-Consent-Testing?secret_password=4nzp5x09db318hcu9e2" style="margin: 12px auto 6px auto; font-family: Helvetica,Arial,Sans-serif; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-size-adjust: none; font-stretch: normal; -x-system-font: none; display: block; text-decoration: underline;">Adult Web Consent Testing</a> <object id="doc_4038" name="doc_4038" height="600" width="100%" type="application/x-shockwave-flash" data="http://d1.scribdassets.com/ScribdViewer.swf" style="outline:none;" >            <param name="movie" value="http://d1.scribdassets.com/ScribdViewer.swf">             <param name="wmode" value="opaque">             <param name="bgcolor" value="#ffffff">             <param name="allowFullScreen" value="true">             <param name="allowScriptAccess" value="always">             <param name="FlashVars" value="document_id=66688220&access_key=key-2jmjwupwefagkjdsw3ad&page=1&viewMode=list">             <embed id="doc_4038" name="doc_4038" src="http://d1.scribdassets.com/ScribdViewer.swf?document_id=66688220&access_key=key-2jmjwupwefagkjdsw3ad&page=1&viewMode=list" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="600" width="100%" wmode="opaque" bgcolor="#ffffff"></embed>         </object>

<div class="line"></div>

<div class="clear">
	<label for="txtConsentEmail">Email Address<span class="required">*</span></label>
	<div class="input">
		<input id="txtConsentEmail" type="text" value="<?= (!empty($email)) ? $email : '' ?>" />
		<p>Must be valid</p>
	</div>
</div>
<div class="clear">
	<label for="txtConsentName">Full Name</label>
	<div class="input">
		<input id="txtConsentName" type="text" />
		<p>Type your name to request that we use your real name if we refer to you in our publications.</p>
		<p><em>We will use your real name where possible; however, we reserve the right to use a fake pseudonym when referring to data that are potentially embarrassing or harmful to you.</em></p>
	</div>
</div>
<div class="clear">
	<div class="input">
		<p><strong>Have you read and understood the information in the document above and do you agree to participate?</strong></p>	
		<input type="button" id="btnAgree" value="Yes, I Agree" />
		<input type="button" id="btnNoThanks" value="No Thanks" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');