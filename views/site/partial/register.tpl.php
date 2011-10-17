<?php

$email = $SOUP->get('email');

$fork = $SOUP->fork();
$fork->set('title', 'Register');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtUsername').focus();
	$("#txtBirthdate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange : '1950:<?= date("Y") ?>', // 1950 thru current year
		dateFormat: 'yy-mm-dd' // MySQL datetime format
		});
	// event handler for Register button
	$("#btnRegister").mousedown(function(){
		registerUser();
	});
	// check if passwords match
	$("#txtPassword").blur(function(){
		checkPasswordMatch();
		});	
	$("#txtConfirmPassword").blur(function(){
		checkPasswordMatch();
		});
	// check username availability
	$("#txtUsername").blur(function(){
		checkUsernameAvailable();
		});
});

function checkPasswordMatch()
{
	var pw = $("#txtPassword").val()
	var pw2 = $("#txtConfirmPassword").val()
	if( (pw != '') && (pw2 != '') ) {
		if(pw == pw2)
			$('#pw_check').removeClass("bad").addClass("good").text("Match");
		else
			$('#pw_check').removeClass("good").addClass("bad").text("No match");
		}
	else {
		$('#pw_check').text("");
	}
}

function checkUsernameAvailable()
{
	var un = $('#txtUsername').val();
	if(un != ''){
		$.post(
			'<?= Url::registerProcess() ?>',
			{ username:un, action:"check" },
			function(data){
				if(data == "available")
					$('#username_check').removeClass("bad").addClass("good").text("Available");
				else if (data == "unavailable")
					$('#username_check').removeClass("good").addClass("bad").text("Not available");
				}
			);
		}
	else {
		$("#username_check").text("");
	}
}

function registerUser()
{
	buildPost({
		'processPage':'<?= Url::registerProcess() ?>',
		'info':{
			action: "register",
			code: $("#hdnInvitationCode").val(),
			uname: $("#txtUsername").val(),
			pw: $("#txtPassword").val(),
			pw2: $("#txtConfirmPassword").val(),
			email: $("#txtEmail").val(),			
			name: $("#txtName").val(),			
			birthdate: $("#txtBirthdate").val(),
			sex: $("#selGender").val(),
			location: $("#txtLocation").val(),
			biography: $('#txtBiography').val()
		},
		'buttonID':'#btnRegister'
		});
}

</script>

<p>Have an account already? <a href="<?= Url::logIn() ?>">Log in here.</a> If not, create one below for free.</p>

<div class="line"></div>

<div class="clear">
	<label for="txtUsername">Username<span class="required">*</span></label>
	<div class="input">
		<input id="txtUsername" type="text" maxlength="20" />
		<span id="username_check"></span>
		<p><strong>Do not use your full name</strong></p>		
		<p>Letters, numbers, and hyphens only, max 20 characters</p>
	</div>
</div>
<div class="clear">
	<label for="txtPassword">Password<span class="required">*</span></label>
	<div class="input">
		<input id="txtPassword" type="password" />
	</div>
</div>		
<div class="clear">
	<label for="txtConfirmPassword">Confirm Password<span class="required">*</span></label>
	<div class="input">
		<input id="txtConfirmPassword" type="password" />
		<span id="pw_check"></span>
	</div>
</div>			
<div class="clear">
	<label for="txtEmail">Email Address<span class="required">*</span></label>
	<div class="input">
		<input id="txtEmail" type="text" maxlength="255" value="<?= (!empty($email)) ? $email : '' ?>" />
		<p>Must be valid to receive email notifications</p>
	</div>
</div>
<div class="clear">
	<label for="txtBirthdate">Birth date<span class="required">*</span></label>
	<div class="input">
		<input id="txtBirthdate" type="text" />
		<p>Will not be visible in profile</p>
	</div>
</div>
<div class="clear">
	<label for="txtName">Name</label>
	<div class="input">
		<input id="txtName" type="text" maxlength="255" />
		<p>Hidden for members younger than 18</p>
	</div>
</div>
<div class="clear">
	<label for="selGender">Gender</label>
	<div class="input">
		<select id="selGender">
			<option value="-">--</option>
			<option value="M">Male</option>
			<option value="F">Female</option>
		</select>	
	</div>
</div>
<div class="clear">
	<label for="txtLocation">Location</label>
	<div class="input">
		<input id="txtLocation" type="text" maxlength="255" />
	</div>
</div>
<div class="clear">
	<label for="txtBiography">About</label>
	<div class="input">
		<textarea id="txtBiography"></textarea>
		<p>Hidden for members younger than 18</p>		
		<p><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
	</div>
</div>
<div class="clear">
	<div class="input">
		<input id="btnRegister" type="button" value="Register" />
	</div>
</div>

<?

$fork->endBlockSet();
$fork->render('site/partial/panel');