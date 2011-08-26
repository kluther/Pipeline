<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$deadline = $project->getDeadline();
$deadline = ($deadline != null) ? '<strong>due</strong> '.formatTimeTag($deadline) : '<strong>no deadline</strong>';

$venue = $project->getVenue();
$venue = ($venue != null) ? $venue : '(none)';

$fork = $SOUP->fork();
$fork->set('id', 'progress');
$fork->set('title', "Progress");
$fork->set('editable', true);
$fork->set('editLabel', "Edit Progress");
$fork->startBlockSet('body');
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#txtDeadline").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd' // MySQL datetime format
	});
	
	$('#selStatus').val('<?= $project->getStatus() ?>');
	
	$('#btnEditProgress').click(function(){
		buildPost({
			'processPage':'<?= Url::detailsProcess($project->getID()) ?>',
			'info': {
				'action':'progress',
				'deadline':$('#txtDeadline').val(),
				'status':$('#selStatus').val()
			},
			'buttonID':'#btnEditProgress'
		});
	});
	
	$("#btnCancelProgress").click(function(){
		$("#progress .edit").hide();
		$("#progress .view").fadeIn();
	});
	
	$("#progress .editButton").click(function(){
		var edit = $("#progress .edit");
		var view = $("#progress .view");
		toggleEditView(edit, view);
		if($(view).is(":hidden"))
			$('#selStatus').focus();
	});		
});
</script>

<div class="view">

<p><strong>started</strong> <?= formatTimeTag($project->getDateCreated()) ?> <span class="slash">/</span> <strong>status</strong>: <span class="status"><?= formatProjectStatus($project->getStatus()) ?></span>  <span class="slash">/</span> <?= $deadline ?></p>

</div><!-- .view -->

<div class="edit hidden">

<div class="clear">
	<label for="selStatus">Status<span class="required">*</span></label>
	<div class="input">
		<select id="selStatus">
			<option value="<?= Project::STATUS_PRE_PRODUCTION ?>"><?= formatProjectStatus(Project::STATUS_PRE_PRODUCTION) ?></option>
			<option value="<?= Project::STATUS_IN_PRODUCTION ?>"><?= formatProjectStatus(Project::STATUS_IN_PRODUCTION) ?></option>
			<option value="<?= Project::STATUS_POST_PRODUCTION ?>"><?= formatProjectStatus(Project::STATUS_POST_PRODUCTION) ?></option>
			<option value="<?= Project::STATUS_COMPLETED ?>"><?= formatProjectStatus(Project::STATUS_COMPLETED) ?></option>
			<option value="<?= Project::STATUS_CANCELED ?>"><?= formatProjectStatus(Project::STATUS_CANCELED) ?></option>
		</select>
	</div>
</div>

<div class="clear">
	<label for="txtDeadline">Deadline</label>
	<div class="input">
		<input id="txtDeadline" type="text" value="<?= ($project->getDeadline() != '') ? date("Y-m-d",strtotime($project->getDeadline())) : '' ?>" />
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnEditProgress" type="button" value="Save" />
		<input id="btnCancelProgress" type="button" value="Cancel" />
	</div>
</div>

</div><!-- .edit -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');