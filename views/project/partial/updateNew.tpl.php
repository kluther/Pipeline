<?php

$project = $SOUP->get('project');
$task = $SOUP->get('task');

$fork = $SOUP->fork();
$fork->set('title', 'New Contribution');
$fork->startBlockSet('body');

?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#txtTitle').focus();
		initializeUploader();
	});
	
	function uploadComplete() {
		buildPost({
			'processPage':'<?= Url::updateNewProcess($task->getID()) ?>',
			'info': $('#frmNewItem').serialize(),
			'buttonID':'#btnCreateUpdate'
		});
	}
</script>

<form id="frmNewItem">

<input type="hidden" name="action" value="create" />

<div class="clear">
	<label for="txtTitle">Title<span class="required">*</span></label>
	<div class="input">
		<input type="text" id="txtTitle" name="txtTitle" maxlength="255" />
		<p>Short title for the contribution</p>
	</div>
</div>

<div class="clear">
	<label for="txtMessage">Message<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtMessage" name="txtMessage"></textarea>
		<p>Write your contribution here, <a class="help-link" href="<?= Url::help() ?>#help-html-allowed">some HTML allowed</a></p>
	</div>
</div>

<div class="clear">
	<label for="selStatus">Status<span class="required">*</span></label>
	<div class="input">
		<select id="selStatus" name="selStatus">
			<option value="<?= Accepted::STATUS_PROGRESS ?>"><?= Accepted::getStatusName(Accepted::STATUS_PROGRESS) ?></option>
			<option value="<?= Accepted::STATUS_FEEDBACK ?>" selected="selected"><?= Accepted::getStatusName(Accepted::STATUS_FEEDBACK) ?></option>
			<option value="<?= Accepted::STATUS_COMPLETED ?>"><?= Accepted::getStatusName(Accepted::STATUS_COMPLETED) ?></option>
			<option value="<?= Accepted::STATUS_RELEASED ?>"><?= Accepted::getStatusName(Accepted::STATUS_RELEASED) ?></option>
		</select>
	</div>
</div>

<div class="clear">
	<label>Attached Files</label>
	<div class="input">
		<input type="button" id="btnSelectFiles" value="Select Files" />
		<p>Max size 100 MB each</p>
		<div id="filelist"></div>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnCreateUpdate" type="button" value="Create Contribution" />
	</div>
</div>

</form>

<?php
	$SOUP->render('site/partial/newUpload', array(
		'uploadButtonID' => 'btnCreateUpdate'
	));
?>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');