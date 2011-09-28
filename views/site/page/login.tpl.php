<?php

$referer = $SOUP->get('referer');

$fork = $SOUP->fork();

$fork->set('pageTitle', "Log In");
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtUsername').focus();
	$('#btnLogIn').mousedown(function(){
		logIn();
		});
	// the below function allows user to press "Enter" to log in
	$('input.login').keypress(function(e){
		if(e.which == 13){
			$('#btnLogIn').mousedown();
			return false;
			}
		});
	});

function logIn(){
	buildPost({
		'processPage':'<?= Url::logInProcess() ?>',
		'info':{
			'username':$('#txtUsername').val(),
			'password':$('#txtPassword').val(),
			'referer':$('#referer').val()
			},
		'buttonID':'#btnLogIn'
		});
	}

</script>

<td class="left">

<label>Username or Email <input id="txtUsername" type="text" class="login" /></label>
<label>Password <input id="txtPassword" type="password" class="login" /></label>
<input type="hidden" id="referer" name="referer" value="<?= $referer ?>" />
<input id="btnLogIn" type="button" value="Log In" />

<p>Don't have an account yet? <a href="<?= Url::consent() ?>">Register for free!</a></p>

</td>

<td class="extra"> </td>

<td class="right"> </td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');