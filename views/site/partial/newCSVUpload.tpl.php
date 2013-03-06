<?php
    $formID = $SOUP->get('formID', 'frmNewItem');
    $browseButtonID = $SOUP->get('browseButtonID', 'selCSV');
    $uploadButtonID = $SOUP->get('uploadButtonID', 'btnCreateTasks');
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
		url : '<?= Url::adminUtilitiesProcess() ?>',  
		unique_names : true,
                multi_selection : false,
                max_file_count : 1,
		//resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : '<?= Url::base() ?>/lib/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '<?= Url::base() ?>/lib/plupload/js/plupload.silverlight.xap',
		filters : [
			{title : "Allowed files", extensions : "csv"}
		]
	});

	uploader.bind('FilesAdded', function(up, files) {
		for (var i in files) {
                    $('#filelist').append('<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>');
		}
                $('#selCSV').hide();
	});
	
	uploader.bind('Error', function(up, error) {
		uploader.stop();
		uploader.destroy();
		displayNotification(error.message, "error");
		initializeUploader();
		$('#<?= $uploadButtonID ?>').removeAttr("disabled");
		$('#<?= $uploadButtonID ?>').removeClass('disabledButton');
	});

	uploader.bind('UploadFile', function(up, file) {
		$('#<?= $formID ?>').append('<input type="hidden" name="file[' + file.id + ']" value="' + file.name + '" />');
                //Track which button is submitting changes
                $.extend(up.settings.multipart_params = {"projectID": $('#selProject').val() });
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#'+file.id+' b').html('<span>' + file.percent + "%</span>");
	});
	
	uploader.bind('UploadComplete', function(up, files) {
		uploadComplete();
                uploadCompleteExtension();
                
	});
	
	uploader.bind('FileUploaded', function(up, file, response) {
		var obj = $.parseJSON(response.response);
		if(obj.error) {
			uploader.stop();
			uploader.destroy();
			initializeUploader();
			$('#<?= $uploadButtonID ?>').removeAttr("disabled");
			$('#<?= $uploadButtonID ?>').removeClass('disabledButton');
                        $('#errorCSV').append(obj.error);
                }
                else {
                        $('#<?= $uploadButtonID ?>').removeAttr("disabled");
			$('#<?= $uploadButtonID ?>').removeClass('disabledButton');
                        //Clear Form
                        $('#frmNewItem')[0].reset();
                        //Clear List
                        $('#filelist').html('');
                        uploader.stop();
			uploader.destroy();
			initializeUploader();
                        window.location = obj.url;
                }
	});

	$('#<?= $uploadButtonID ?>').mousedown(function(){
		uploader.start();
		$(this).attr('disabled', 'disabled');
		$(this).addClass('disabledButton');
		return false;
	});
        
        $('#btnCancel').click(function(){
                    //Clear Form
                    $('#frmNewItem')[0].reset();
                    //Clear List
                    $('#filelist').html('');
                    $('#selCSV').show();
                    uploader.stop();
                    uploader.destroy();
                    initializeUploader();
                    //Allow Form Hide
                    //Some contexts, such as within the task page, will want to allow the form to be hidden
                    allowFormHide();
        });

	uploader.init();
}

</script>