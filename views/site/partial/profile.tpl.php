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

// must be current user to edit
$hasPermission = ($user->getID() == Session::getUserID());

$fork = $SOUP->fork();
$fork->set('id', 'profile');
$fork->set('title', "Profile");
$fork->set('editable', $hasPermission);
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.gears.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.flash.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.html4.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.html5.js"></script>

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
		if($(view).is(":hidden")) {
			$('#txtEmail').focus();
			initializeUploader();
		}
	});	
	
	// check if passwords match
	$("#txtPassword").blur(function(){
		checkPasswordMatch();
		});	
	$("#txtConfirmPassword").blur(function(){
		checkPasswordMatch();
		});	
	
	<?php if($user->getPicture() != null): ?>
	
	$('#btnRemovePicture').click(function(){
		buildPost({
			'processPage': '<?= Url::userPictureProcess(Session::getUserID()) ?>',
			'info':{
				'action':'remove-picture'
			},
			'buttonID': '#btnRemovePicture'
		});
	});
	
	<?php endif; ?>

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

function initializeUploader() {

	var uploadButtonID = 'btnUploadPicture';

	// // clear file list
	// $('#filelist').html('');

	var uploader = new plupload.Uploader({
		runtimes : 'flash,html5,gears,silverlight,browserplus',
		browse_button : uploadButtonID,
		max_file_size : '500kb',
		chunk_size : '100kb',
		url : '<?= Url::userPictureProcess(Session::getUserID()) ?>',
		unique_names : true,
		//resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : '<?= Url::base() ?>/lib/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '<?= Url::base() ?>/lib/plupload/js/plupload.silverlight.xap',
		filters : [
			{title : "Allowed files", extensions : "jpg,jpeg,gif,png"}
		]
	});
	
	uploader.init();

	// uploader.bind('Init', function(up, params) {
		// $('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
	// });

	uploader.bind('FilesAdded', function(up, files) {
		for (var i in files) {
			$('#picture-filename').html(files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <strong></strong>');
		}
		$('#'+uploadButtonID).attr('disabled','disabled');
		$('#'+uploadButtonID).addClass('disabledButton');						
		uploader.start();		
	});
	
	uploader.bind('Error', function(up, error) {
		// stop
		uploader.stop();
		uploader.destroy();
		displayNotification(error.message, "error");
		// reset
		$('#'+uploadButtonID).removeAttr("disabled");
		$('#'+uploadButtonID).removeClass('disabledButton');		
		initializeUploader();
	});

	// uploader.bind('UploadFile', function(up, file) {

	// });

	uploader.bind('UploadProgress', function(up, file) {
		$('#picture-filename strong').text(file.percent + "%");
	});
	
	uploader.bind('UploadComplete', function(up, files) {
		displayNotification('Picture uploaded.');
		$('#'+uploadButtonID).removeAttr("disabled");
		$('#'+uploadButtonID).removeClass('disabledButton');			
	});
	
	uploader.bind('FileUploaded', function(up, file, response) {
		var obj = $.parseJSON(response.response);
		if(obj.error) {
			// stop
			uploader.stop();
			uploader.destroy();
			displayNotification(obj.error.message, "error");
			// reset
			$('#'+uploadButtonID).removeAttr("disabled");
			$('#'+uploadButtonID).removeClass('disabledButton');			
			initializeUploader();			
		} else {
			$('#picture-filename').html('');
			$('#user-picture').attr('src','');
			$('#user-picture').attr('src','<?= Url::userPicturesLarge() ?>/'+obj.id);
		}
	});
}

</script>

<div class="edit hidden">

<form id="frmEditItem">

<input type="hidden" name="action" value="edit" />

<div class="clear">
	<label>Picture</label>
	<div class="input">
		<img id="user-picture" src="<?= Url::userPictureLarge($user->getID()) ?>" alt="Profile picture" />		
		<input id="btnUploadPicture" type="button" value="Upload" /> <span id="picture-filename"></span>
		<?php if($user->getPicture() != null): ?>
			<input id="btnRemovePicture" type="button" value="Remove" />
		<?php endif; ?>	
		<p>Square, max 500 kb, must be .jpg, .gif, or .png</p>
	</div>
</div>
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

<?php endif; ?>

<div class="view">

<?= formatUserPicture($user->getID()) ?>
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
	echo '<p class="biography">'.formatParagraphs($bio).'</p>';
}
?>
<div class="clear"></div>
</div><!-- .view -->



<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');