<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$deadline = $project->getDeadline();
$deadline = ($deadline != null) ? formatTimeTag($deadline) : '(none)';

$venue = $project->getVenue();
$venue = ($venue != null) ? $venue : '(none)';

// only organizers or creator may edit
$hasPermission = ( Session::isAdmin() ||
					ProjectUser::isOrganizer(Session::getUserID(), $project->getID()) ||
					ProjectUser::isCreator(Session::getUserID(), $project->getID()) );

$fork = $SOUP->fork();
$fork->set('id', 'progress');
$fork->set('title', "Progress");
$fork->set('editable', $hasPermission);
//$fork->set('editLabel', "Edit Progress");
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

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

<?php endif; ?>

<div class="view">

<ul class="segmented-list">
	<li><strong>Status</strong>: <span class="status"><?= formatProjectStatus($project->getStatus()) ?></span></li>
	<li><strong>Deadline</strong>: <?= $deadline ?></li>
	<li><strong>Started</strong>: <?= formatTimeTag($project->getDateCreated()) ?></li>
</ul>

</div><!-- .view -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');