<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$user = $SOUP->get('user');
$title = $SOUP->get('title', 'Profile');

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

// name
$name = $user->getName();

// must be current user to edit
$hasPermission = ($user->getID() == Session::getUserID());

$fork = $SOUP->fork();
$fork->set('id', 'profile');
$fork->set('title', $title);
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
	$('#btnEditProfile').click(function(){
		buildPost({
			'processPage':'<?= Url::userProcess($user->getID()) ?>',
			'info': $('#frmEditItem').serialize(),
			'buttonID':'#btnEditProfile'
		});
	});
	
	// select drop-down menus
	$("#selGender").val('<?= $user->getSex() ?>');
	$('#selBirthMonth').val('<?= date("n", $dob) ?>');
	$('#selBirthYear').val('<?= date("Y", $dob) ?>');			
	
	$("#btnCancelProfile").click(function(){
		$("#profile .edit").hide();
		$("#profile .view").fadeIn();
		$("#profile .editButton").fadeIn();
	});
	
	$("#profile .editButton").click(function(){
		$(this).hide();
		$("#profile .view").hide();
		$("#profile .edit").fadeIn();
		$('#txtEmail').focus();
		initializeUploader();
	});
	
	// check if passwords match
	$("#txtPassword").blur(function(){
		checkPasswordMatch();
		});	
	$("#txtConfirmPassword").blur(function(){
		checkPasswordMatch();
		});	
	
	<?php if( ($user->getPicture() != null) && ($age >= 18) ): ?>
	
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

<?php if($age >= 18): ?>

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

<?php endif; ?>

</script>

<div class="edit hidden">

<form id="frmEditItem">

<input type="hidden" name="action" value="edit" />

<?php if($age >= 18): ?>
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
<?php endif; ?>
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
	<label for="txtBirthdate">Birth date<span class="required">*</span></label>
	<div class="input">
		<select id="selBirthMonth" name="selBirthMonth">
			<option value="0"></option>
			<option value="1">January</option>
			<option value="2">February</option>
			<option value="3">March</option>
			<option value="4">April</option>
			<option value="5">May</option>
			<option value="6">June</option>
			<option value="7">July</option>
			<option value="8">August</option>
			<option value="9">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select>
		<select id="selBirthYear" name="selBirthYear">
			<option value="0"></option>
			<option value="2011">2011</option>
			<option value="2010">2010</option>
			<option value="2009">2009</option>
			<option value="2008">2008</option>
			<option value="2007">2007</option>
			<option value="2006">2006</option>
			<option value="2005">2005</option>
			<option value="2004">2004</option>
			<option value="2003">2003</option>
			<option value="2002">2002</option>
			<option value="2001">2001</option>
			<option value="2000">2000</option>
			<option value="1999">1999</option>
			<option value="1998">1998</option>
			<option value="1997">1997</option>
			<option value="1996">1996</option>
			<option value="1995">1995</option>
			<option value="1994">1994</option>
			<option value="1993">1993</option>
			<option value="1992">1992</option>
			<option value="1991">1991</option>
			<option value="1990">1990</option>
			<option value="1989">1989</option>
			<option value="1988">1988</option>
			<option value="1987">1987</option>
			<option value="1986">1986</option>
			<option value="1985">1985</option>
			<option value="1984">1984</option>
			<option value="1983">1983</option>
			<option value="1982">1982</option>
			<option value="1981">1981</option>
			<option value="1980">1980</option>
			<option value="1979">1979</option>
			<option value="1978">1978</option>
			<option value="1977">1977</option>
			<option value="1976">1976</option>
			<option value="1975">1975</option>
			<option value="1974">1974</option>
			<option value="1973">1973</option>
			<option value="1972">1972</option>
			<option value="1971">1971</option>
			<option value="1970">1970</option>
			<option value="1969">1969</option>
			<option value="1968">1968</option>
			<option value="1967">1967</option>
			<option value="1966">1966</option>
			<option value="1965">1965</option>
			<option value="1964">1964</option>
			<option value="1963">1963</option>
			<option value="1962">1962</option>
			<option value="1961">1961</option>
			<option value="1960">1960</option>
			<option value="1959">1959</option>
			<option value="1958">1958</option>
			<option value="1957">1957</option>
			<option value="1956">1956</option>
			<option value="1955">1955</option>
			<option value="1954">1954</option>
			<option value="1953">1953</option>
			<option value="1952">1952</option>
			<option value="1951">1951</option>
			<option value="1950">1950</option>
			<option value="1949">1949</option>
			<option value="1948">1948</option>
			<option value="1947">1947</option>
			<option value="1946">1946</option>
			<option value="1945">1945</option>
			<option value="1944">1944</option>
			<option value="1943">1943</option>
			<option value="1942">1942</option>
			<option value="1941">1941</option>
			<option value="1940">1940</option>
			<option value="1939">1939</option>
			<option value="1938">1938</option>
			<option value="1937">1937</option>
			<option value="1936">1936</option>
			<option value="1935">1935</option>
			<option value="1934">1934</option>
			<option value="1933">1933</option>
			<option value="1932">1932</option>
			<option value="1931">1931</option>
			<option value="1930">1930</option>
			<option value="1929">1929</option>
			<option value="1928">1928</option>
			<option value="1927">1927</option>
			<option value="1926">1926</option>
			<option value="1925">1925</option>
			<option value="1924">1924</option>
			<option value="1923">1923</option>
			<option value="1922">1922</option>
			<option value="1921">1921</option>
			<option value="1920">1920</option>
			<option value="1919">1919</option>
			<option value="1918">1918</option>
			<option value="1917">1917</option>
			<option value="1916">1916</option>
			<option value="1915">1915</option>
			<option value="1914">1914</option>
			<option value="1913">1913</option>
			<option value="1912">1912</option>
			<option value="1911">1911</option>
			<option value="1910">1910</option>
			<option value="1909">1909</option>
			<option value="1908">1908</option>
			<option value="1907">1907</option>
			<option value="1906">1906</option>
			<option value="1905">1905</option>
			<option value="1904">1904</option>
			<option value="1903">1903</option>
			<option value="1902">1902</option>
			<option value="1901">1901</option>
			<option value="1900">1900</option>	
		</select>
		<p>Will not be visible in profile</p>
	</div>
</div>	
<div class="clear">
	<label for="txtName">Name</label>
	<div class="input">
		<input id="txtName" name="txtName" type="text" maxlength="255" value="<?= $user->getName() ?>" />
		<p>Hidden for members younger than 18</p>
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
		<textarea id="txtBiography" name="txtBiography"><?= $bio ?></textarea>
		<p>Hidden for members younger than 18</p>
		<p><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
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
<p class="contact"><?= (!empty($name) && $age >= 18) ? $name.$slash : '' ?> <a href="mailto:<?= $user->getEmail() ?>">send email</a> <span class="slash">/</span> last login <?= formatTimeTag($user->getLastLogin()) ?></p>
<?php

echo '<p class="asl">';
//	echo $age.' years old';
if(!empty($sex)) {
	echo $sex;
}
if(!empty($sex) && !empty($loc)) {
	echo $slash;
}
if(!empty($loc)) {
	echo 'from '.$loc;
}
echo '</p>';	
if(!empty($bio)) {
	echo '<div class="line" style="margin: 1em 0 0 55px;"></div>';
	echo '<p class="biography">'.formatParagraphs($bio).'</p>';
}	

?>
<div class="clear"></div>
</div><!-- .view -->


<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');