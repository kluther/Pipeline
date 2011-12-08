<?php

$referer = $SOUP->get('referer');

$fork = $SOUP->fork();

$fork->set('pageTitle', "Log In");
$fork->set('headingURL', Url::logIn());
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtUsername').focus();
	$('#btnLogIn').click(function(){
		buildPost({
			'processPage':'<?= Url::logInProcess() ?>',
			'info':{
				'username':$('#txtUsername').val(),
				'password':$('#txtPassword').val(),
				'remember':$('#chkRemember').val(),
				'referer':$('#referer').val(),
				'action':'login'
				},
			'buttonID':'#btnLogIn'
			});
	});
	// the below function allows user to press "Enter" to log in
	$('div.login').keypress(function(e){
		if(e.which == 13){
			$('#btnLogIn').click();
			return false;
			}
		});
	});

</script>

<td class="left">

<div class="login">

	<label>Username or Email <input id="txtUsername" type="text" /></label>
	<label>Password <input id="txtPassword" type="password" /></label>
	<label><input type="checkbox" id="chkRemember" value="remember" /> Remember login for 30 days</label>
	<input type="hidden" id="referer" name="referer" value="<?= $referer ?>" />
	<input id="btnLogIn" type="button" value="Log In" />

</div>

</td>

<td class="right">

	<p>Forgot your password? <a href="<?= Url::forgotPassword() ?>">Reset it here.</a></p>
	<p>Don't have an account yet? <a href="<?= Url::consent() ?>">Register for free!</a></p>

</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');