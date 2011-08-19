<?php

$token = $SOUP->get('token', '');
$itemType = $SOUP->get('item_type', '');
$itemID = $SOUP->get('item_id', '');

?>
<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/jquery.fileupload-ui.css" />
<script type="text/javascript" src="<?= Url::scripts() ?>/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?= Url::scripts() ?>/jquery.fileupload-ui.js"></script>
<script type="text/javascript" src="<?= Url::scripts() ?>/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	'use strict';

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload();

	// Load existing files:
	$.getJSON($('#fileupload form').prop('action'),
		{
			'token':'<?= $token ?>',
			'item_type': '<?= $itemType ?>',
			'item_id': '<?= $itemID ?>'
		},
		function (files) {
		var fu = $('#fileupload').data('fileupload');
		fu._adjustMaxNumberOfFiles(-files.length);
		fu._renderDownload(files)
			.appendTo($('#fileupload .files'))
			.fadeIn(function () {
				// Fix for IE7 and lower:
				$(this).show();
			});
	});

	// Open download dialogs via iframes,
	// to prevent aborting current uploads:
	$('#fileupload .files a:not([target^=_blank])').live('click', function (e) {
		e.preventDefault();
		$('<iframe style="display:none;"></iframe>')
			.prop('src', this.href)
			.appendTo('body');
	});
});

</script>

<div id="fileupload">
	<form action="<?= Url::uploadProcess() ?>" method="POST" enctype="multipart/form-data">
		<div class="fileupload-buttonbar">
			<label class="fileinput-button">
				<span>Add files...</span>
				<input type="file" name="files[]" multiple="multiple" />
			</label>
			<!--button type="submit" class="start">Start upload</button-->
			<button type="reset" class="cancel">Cancel upload</button>
			<button type="button" class="delete">Delete files</button>
			<input type="hidden" name="token" value="<?= $token ?>" />
		</div>
	</form>
	<div class="fileupload-content">
		<table class="files"></table>
		<div class="fileupload-progressbar"></div>
	</div>
</div>		

<script id="template-upload" type="text/x-jquery-tmpl">
	<tr class="template-upload{{if error}} ui-state-error{{/if}}">
		<td class="preview"></td>
		<td class="name">${name}</td>
		<td class="size">${sizef}</td>
		{{if error}}
			<td class="error" colspan="2">Error:
				{{if error === 'maxFileSize'}}File is too big
				{{else error === 'minFileSize'}}File is too small
				{{else error === 'acceptFileTypes'}}Filetype not allowed
				{{else error === 'maxNumberOfFiles'}}Max number of files exceeded
				{{else}}${error}
				{{/if}}
			</td>
		{{else}}
			<td class="progress"><div></div></td>
			<td class="start"><button>Start</button></td>
		{{/if}}
		<td class="cancel"><button>Cancel</button></td>
	</tr>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
	<tr class="template-download{{if error}} ui-state-error{{/if}}">
		{{if error}}
			<td></td>
			<td class="name">${name}</td>
			<td class="size">${sizef}</td>
			<td class="error" colspan="2">Error:
				{{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
				{{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
				{{else error === 3}}File was only partially uploaded
				{{else error === 4}}No File was uploaded
				{{else error === 5}}Missing a temporary folder
				{{else error === 6}}Failed to write file to disk
				{{else error === 7}}File upload stopped by extension
				{{else error === 'maxFileSize'}}File is too big
				{{else error === 'minFileSize'}}File is too small
				{{else error === 'acceptFileTypes'}}Filetype not allowed
				{{else error === 'maxNumberOfFiles'}}Max number of files exceeded
				{{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
				{{else error === 'emptyResult'}}Empty file upload result
				{{else}}${error}
				{{/if}}
			</td>
		{{else}}
			<td class="preview">
				{{if thumbnail_url}}
					<a href="${url}" target="_blank"><img src="${thumbnail_url}" width="125"></a>
				{{/if}}
			</td>
			<td class="name">
				<a href="${url}"{{if thumbnail_url}} target="_blank"{{/if}}>${name}</a>
			</td>
			<td class="size">${sizef}</td>
			<td colspan="2"></td>
		{{/if}}
		<td class="delete">
			<button data-type="${delete_type}" data-url="${delete_url}">Delete</button>
		</td>
	</tr>
</script>	