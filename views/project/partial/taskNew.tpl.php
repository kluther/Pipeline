<?php

$project = $SOUP->get('project');

$fork = $SOUP->fork();
$fork->set('title', "New Task");
$fork->startBlockSet('body');

?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#txtDeadline").datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd' // MySQL datetime format
		});
		
		$('#txtLeader').autocomplete({
			source: '<?= Url::people($project->getID()) ?>/search/organizers',
			minLength: 2
		});
		
		$('#txtTitle').focus();		
	});
	
	function uploadComplete() {
		buildPost({
			'processPage':'<?= Url::taskNewProcess($project->getID()) ?>',
			'info': $('#frmNewItem').serialize(),
			'buttonID':'#btnCreateTask'
		});
	}
</script>

<form id="frmNewItem">

<input type="hidden" name="action" value="create" />

<div class="clear">
	<label for="txtTitle">Title<span class="required">*</span></label>
	<div class="input">
		<input id="txtTitle" name="txtTitle" type="text" maxlength="255" />
		<p>Short description of this task</p>
	</div>
</div>

<div class="clear">
	<label for="txtLeader">Leader<span class="required">*</span></label>
	<div class="input">
		<input id="txtLeader" name="txtLeader" type="text" />
		<p>An organizer to lead this task</p>
	</div>
</div>

<div class="clear">
	<label for="txtDescription">Instructions<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtDescription" name="txtDescription"></textarea>
	</div>
</div>

<div class="clear">
	<label for="selStatus">Status<span class="required">*</span></label>
	<div class="input">
		<select id="selStatus" name="selStatus">
			<option value="<?= Task::STATUS_OPEN ?>" selected="selected"><?= Task::getStatusName(Task::STATUS_OPEN) ?></option>
			<option value="<?= Task::STATUS_CLOSED ?>"><?= Task::getStatusName(Task::STATUS_CLOSED) ?></option>
		</select>
	</div>
</div>

<div class="clear">
	<label for="txtNumNeeded"># People Needed</label>
	<div class="input">
		<input id="txtNumNeeded" name="txtNumNeeded" type="text" />
		<p>Number of people needed for this task<br />
		(Leave empty for unlimited)</p>
	</div>
</div>

<div class="clear">
	<label for="txtDeadline" name="txtDeadline">Deadline</label>
	<div class="input">
		<input id="txtDeadline" name="txtDeadline" type="text" />
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
		<input id="btnCreateTask" type="button" value="Create Task" />
	</div>
</div>

</form>

<?php
	$SOUP->render('site/partial/newUpload', array(
		'uploadButtonID' => 'btnCreateTask'
	));
?>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');