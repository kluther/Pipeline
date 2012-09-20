<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$deadline = $project->getDeadline();
$deadline = ($deadline != null) ? formatTimeTag($deadline) : '(none)';

// $venue = $project->getVenue();
// $venue = ($venue != null) ? $venue : '(none)';

// admin, trusted, creator may edit
$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

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
	
        
        var isPrivate = <?= $project->getPrivate() ?> ;
        (isPrivate == 1) ? true:false;
        
        $('#chkPrivate').prop("checked",isPrivate);
        
	$('#btnEditProgress').click(function(){
		buildPost({
			'processPage':'<?= Url::detailsProcess($project->getID()) ?>',
			'info': {
				'action':'progress',
				'deadline':$('#txtDeadline').val(),
				'status':$('#selStatus').val(),
                                'private':$('#chkPrivate:checked').val()
                                
			},
			'buttonID':'#btnEditProgress'
		});
	});	
	
	$("#progress .editButton").click(function(){
		$(this).hide();
		$("#progress .view").hide();
		$("#progress .edit").fadeIn();
		$('#selStatus').focus();			
	});
	
	$("#btnCancelProgress").click(function(){
		$("#progress .edit").hide();
		$("#progress .view").fadeIn();
		$("#progress .editButton").fadeIn();
	});		
});
</script>

<div class="edit hidden">

<!--Controls Status drop-down list-->
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

<!-- Controls Deadline date picker-->
<div class="clear">
	<label for="txtDeadline">Deadline</label>
	<div class="input">
		<input id="txtDeadline" type="text" value="<?= ($project->getDeadline() != '') ? date("Y-m-d",strtotime($project->getDeadline())) : '' ?>" />
	</div>
</div>

<!-- Controls for Project Privacy -->
<div class="clear">
        <label for="chkPrivate">Private</label>
	<div class="input">
		<input type="checkbox" id="chkPrivate" name="chkPrivate" value="private" />
		<p>If checked, this project will be hidden from everyone except members and invitees.</p>
	</div>
</div>


<!-- Controls Save and Cancel buttons-->
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
        <li><strong></strong><?= formatIsPrivate($project->getPrivate()) ?></li>
</ul>

</div><!-- .view -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');