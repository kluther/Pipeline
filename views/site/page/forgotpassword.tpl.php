<?php

$fork = $SOUP->fork();

$fork->set('pageTitle', "Reset Password");
$fork->set('headingURL', Url::forgotPassword());
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtUsername').focus();
	$('#btnResetPassword').click(function(){
		buildPost({
			'processPage':'<?= Url::logInProcess() ?>',
			'info':{
				'username':$('#txtUsername').val(),
				'action':'reset'
				},
			'buttonID':'#btnResetPassword'
			});
	});
	// the below function allows user to press "Enter" to log in
	$('input.login').keypress(function(e){
		if(e.which == 13){
			$('#btnResetPassword').click();
			return false;
			}
		});	
});

</script>

<td class="left">

<p>Forgot your password? No big deal.</p>

<p>Type in your username or email address below. When you click "Reset Password," your account password will be reset and a new password will be sent to the email address registered to that account.</p>

<div class="line"> </div>

<label>Username or Email <input id="txtUsername" type="text" class="login" /></label>
<input id="btnResetPassword" type="button" value="Reset Password" />

</td>

<td class="right"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');