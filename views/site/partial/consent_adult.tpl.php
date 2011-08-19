<?php

$fork = $SOUP->fork();
$fork->startBlockSet('body');

?>
<script type="text/javascript">

$(document).ready(function(){
	$('#txtConsentEmail').focus();
	
	$('#btnAgree').mousedown(function(){
		var email = $('#txtConsentEmail').val();
		var name = $('#txtConsentName').val();
		
		buildPost({
			'processPage':'<?= Url::consentProcess() ?>',
			'info':{
				'email':email,
				'name':name
			},
			'buttonID':'#btnAgree'
		});
	});
	
	$('#btnNoThanks').mousedown(function(){
		window.location = '<?= Url::base() ?>';
	});
	
});

</script>

(consent form goes here)

<div class="line"></div>

<div class="clear">
	<label for="txtConsentEmail">Email Address<span class="required">*</span></label>
	<div class="input">
		<input id="txtConsentEmail" type="text" />
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
		<p><strong>Have read and understood the information on this page and do you agree to participate?</strong></p>	
		<input type="button" id="btnAgree" value="Yes, I Agree" />
		<input type="button" id="btnNoThanks" value="No Thanks" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');