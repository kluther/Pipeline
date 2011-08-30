<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$user = $SOUP->get('user');

// separator
$slash = ' <span class="slash">/</span> ';

// date of birth
$dob = $user->getDOB();
$age = $dob;
if($age != null) {
	$dob = strtotime($dob);
	$diff = time() - $dob;
	$years = (int) ($diff / (60 * 60 * 24 * 365));
	$age = $years;
}

// sex
$sex = $user->getSex();
if($sex == 'M') {
	$sex = 'male';
} elseif($sex == 'F') {
	$sex = 'female';
} elseif($sex == '-') {
	$sex = null;
}

// location
$loc = $user->getLocation();

// bio
$bio = $user->getBiography();

$fork = $SOUP->fork();
$fork->set('id', 'profile');
$fork->set('title', "Profile");
$fork->set('editable', true);
$fork->startBlockSet('body');
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#txtBirthdate").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd' // MySQL datetime format
	});

	$('#btnEditProfile').click(function(){
		buildPost({
			'processPage':'<?= Url::userProcess($user->getID()) ?>',
			'info': $('#frmEditItem').serialize(),
			'buttonID':'#btnEditProfile'
		});
	});
	
	$("#selGender").val('<?= $user->getSex() ?>');
	
	$("#btnCancelProfile").click(function(){
		$("#profile .edit").hide();
		$("#profile .view").fadeIn();
	});
	
	$("#profile .editButton").click(function(){
		var edit = $("#profile .edit");
		var view = $("#profile .view");
		toggleEditView(edit, view);
		if($(view).is(":hidden"))
			$('#txtEmail').focus();
	});	
});
</script>

<div class="view">

<a class="picture large" href="<?= Url::user($user->getID()) ?>"><img src="<?= Url::userPictureLarge($user->getID()) ?>" /></a>
<h5 class="username"><?= formatUserLink($user->getID()) ?></h5>
<p class="contact"><?= ($user->getName() != null) ? $user->getName().$slash : '' ?> <a href="mailto:<?= $user->getEmail() ?>">send email</a></p>
<?php
if( ($age != null) &&
	($sex != null) &&
	($loc != null) ) {
	echo '<p class="asl">'.$age.' years old'.$slash.$sex.$slash.'from '.$loc.'</p>';
} elseif( ($age != null) &&
	($sex != null) ) {
	echo '<p class="asl">'.$age.' years old'.$slash.$sex.'</p>';
} elseif( ($age != null) &&
	($loc != null) ) {
	echo '<p class="asl">'.$age.' years old'.$slash.'from '.$loc.'</p>';
} elseif( ($sex != null) &&
	($loc != null) ) {
	echo '<p class="asl">'.$sex.$slash.'from '.$loc.'</p>';	
} elseif($age != null) {
	echo '<p class="asl">'.$age.' years old</p>';
} elseif($sex != null) {
	echo '<p class="asl">'.$sex.'</p>';
} elseif($loc != null) {
	echo '<p class="asl">from '.$loc.'</p>';
}

if($bio != null) {
	echo '<div class="line" style="margin: 1em 0 0 55px;"></div>';
	echo '<p class="biography">'.html_entity_decode($bio).'</p>';
}
?>
<div class="clear"></div>
</div><!-- .view -->

<div class="edit hidden">

<form id="frmEditItem">

<input type="hidden" name="action" value="edit" />

<!--div class="clear">
	<label for="">Picture</label>
	<div class="input">

	</div>
</div-->
<div class="clear">
	<label for="txtEmail">Email Address<span class="required">*</span></label>
	<div class="input">
		<input id="txtEmail" name="txtEmail" type="text" maxlength="255" value="<?= $user->getEmail() ?>" />
		<p>Must be valid to receive email notifications</p>
	</div>
</div>
<div class="clear">
	<label for="txtPassword">New Password</label>
	<div class="input">
		<input id="txtPassword" name="txtPassword" type="password" />
		<p>Only needed if you're changing your password</p>
	</div>
</div>		
<div class="clear">
	<label for="txtConfirmPassword">Confirm Password</label>
	<div class="input">
		<input id="txtConfirmPassword" name="txtConfirmPassword" type="password" />
		<span id="pw_check"></span>
	</div>
</div>	
<div class="clear">
	<label for="txtName">Name</label>
	<div class="input">
		<input id="txtName" name="txtName" type="text" maxlength="255" value="<?= $user->getName() ?>" />
		<p>Your real name</p>
	</div>
</div>
<div class="clear">
	<label for="txtBirthdate">Birthdate</label>
	<div class="input">
		<input id="txtBirthdate" name="txtBirthdate" type="text" value="<?= ($user->getDOB() != '') ? date("Y-m-d",strtotime($user->getDOB())) : '' ?>" />
	</div>
</div>
<div class="clear">
	<label for="selGender">Gender</label>
	<div class="input">
		<select id="selGender" name="selGender">
			<option value="-">--</option>
			<option value="M">Male</option>
			<option value="F">Female</option>
		</select>
	</div>
</div>
<div class="clear">
	<label for="txtLocation">Location</label>
	<div class="input">
		<input id="txtLocation" name="txtLocation" type="text" maxlength="255" value="<?= $loc ?>" />
	</div>
</div>
<div class="clear">
	<label for="txtBiography">About</label>
	<div class="input">
		<textarea id="txtBiography" name="txtBiography"><?= html_entity_decode($bio) ?></textarea>
		<p>A bit about yourself</p>
	</div>
</div>
<div class="clear">
	<div class="input">
		<input id="btnEditProfile" type="button" value="Save" />
		<input id="btnCancelProfile" type="button" value="Cancel" />
	</div>
</div>
</form>

</div><!-- .edit -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');