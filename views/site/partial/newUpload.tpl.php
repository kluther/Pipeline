<?php

$formID = $SOUP->get('formID', 'frmNewItem');
$browseButtonID = $SOUP->get('browseButtonID', 'btnSelectFiles');
$uploadButtonID = $SOUP->get('uploadButtonID', 'btnSubmit');

?>

<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.gears.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.flash.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.html4.js"></script>
<script type="text/javascript" src="<?= Url::base() ?>/lib/plupload/js/plupload.html5.js"></script>
<script type="text/javascript">

function initializeUploader() {
	// clear file list
	$('#filelist').html('');
	
	// clear hidden file fields
	$('#<?= $formID ?>').find('input[name^="file"]').remove();

	var uploader = new plupload.Uploader({
		runtimes : 'flash,html5,gears,silverlight,browserplus',
		browse_button : '<?= $browseButtonID ?>',
		max_file_size : '100mb',
		chunk_size : '1mb',
		url : '<?= Url::uploadProcess() ?>',
		unique_names : true,
		//resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : '<?= Url::base() ?>/lib/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '<?= Url::base() ?>/lib/plupload/js/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,jpeg,gif,png"},
			{title : "Video files", extensions : "mov,avi,mpg"},
			{title : "Flash files", extensions : "swf,fla,flv"},
			{title : "Audio files", extensions : "mp3"}
		]
	});

	// uploader.bind('Init', function(up, params) {
		// $('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
	// });

	uploader.bind('FilesAdded', function(up, files) {
		for (var i in files) {
			$('#filelist').append('<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>');
		}
	});
	
	uploader.bind('Error', function(up, error) {
		uploader.stop();
		uploader.destroy();
		displayNotification(error.message, "error");
		initializeUploader();
	});

	uploader.bind('UploadFile', function(up, file) {
		$('#<?= $formID ?>').append('<input type="hidden" name="file[' + file.id + ']" value="' + file.name + '" />');
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#'+file.id+' b').html('<span>' + file.percent + "%</span>");
	});
	
	uploader.bind('UploadComplete', function(up, files) {
		uploadComplete();
	});
	
	uploader.bind('FileUploaded', function(up, file, response) {
		var obj = $.parseJSON(response.response);
		if(obj.error) {
			uploader.stop();
			uploader.destroy();
			displayNotification(obj.error.message, "error");
			initializeUploader();
		}
	});

	$('#<?= $uploadButtonID ?>').click(function(){
		uploader.start();
		return false;
	});

	uploader.init();
}

$(document).ready(function(){
	initializeUploader();
});

</script>