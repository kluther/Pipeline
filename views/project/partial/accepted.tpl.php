<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$accepted = $SOUP->get('accepted');
$task = $SOUP->get('task');

$fork = $SOUP->fork();
//$fork->set('title', 'Updates Info');
$fork->set('creatable', true);
$fork->set('createLabel', 'New Update');
$fork->set('editable', true);
$fork->set('editLabel', 'Edit Status');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function() {
	$("#btnCancelStatus").mousedown(function(){
		$("#accepted .edit").hide();
		$("#accepted .view").fadeIn();
	});
	$('#btnEditStatus').click(function() {
		buildPost({
			'processPage':'<?= Url::updatesProcess($accepted->getID()) ?>',
			'info':{
				'action':'edit-status',
				'status':$('#selStatus').val()
			},
			'buttonID':'#btnEditStatus'
		});
	});
	$("#accepted .editButton").click(function(){
		var edit = $("#accepted .edit");
		var view = $("#accepted .view");
		toggleEditView(edit, view);
		if($(view).is(":hidden")) {
			$('#selStatus').val('<?= $accepted->getStatus() ?>');
			$('#selStatus').focus();
		}
	});
	$("#accepted .createButton").click(function(){
		window.location = '<?= Url::updatesNew($accepted->getID()) ?>';
	});	
});

</script>

<div class="view">

	<div class="clear">
		<label>Task</label>
		<div class="task-info">
			<p><a href="<?= Url::task($task->getID()) ?>"><?= $task->getTitle() ?></a></p>
		</div>
	</div>
	
	<div class="clear">
		<label>Accepted By</label>
		<div class="task-info">
			<p><a class="picture small" href="<?= Url::user($task->getLeaderID()) ?>"><img src="<?= Url::userPictureSmall($task->getLeaderID()) ?>" /></a><?= formatUserLink($accepted->getCreatorID()) ?></p>
		</div>
	</div>
	
	<div class="clear">
		<label>Status</label>
		<div class="task-info">
			<p><?= Accepted::getStatusName($accepted->getStatus()) ?></p>
		</div>
	</div>		

</div>

<div class="edit hidden">

	<div class="clear">
		<label>Task</label>
		<div class="task-info">
			<p><a href="<?= Url::task($task->getID()) ?>"><?= $task->getTitle() ?></a></p>
		</div>
	</div>
	
	<div class="clear">
		<label>Accepted By</label>
		<div class="task-info">
			<p><a class="picture small" href="<?= Url::user($task->getLeaderID()) ?>"><img src="<?= Url::userPictureSmall($task->getLeaderID()) ?>" /></a><?= formatUserLink($accepted->getCreatorID()) ?></p>
		</div>
	</div>

	<div class="clear">
		<label>Status<span class="required">*</span></label>
		<div class="input">
			<select id="selStatus">
				<option value="<?= Accepted::STATUS_RELEASED ?>"><?= Accepted::getStatusName(Accepted::STATUS_RELEASED) ?></option>
				<option value="<?= Accepted::STATUS_ACCEPTED ?>"><?= Accepted::getStatusName(Accepted::STATUS_ACCEPTED) ?></option>
				<option value="<?= Accepted::STATUS_FEEDBACK ?>"><?= Accepted::getStatusName(Accepted::STATUS_FEEDBACK) ?></option>
				<option value="<?= Accepted::STATUS_COMPLETED ?>"><?= Accepted::getStatusName(Accepted::STATUS_COMPLETED) ?></option>
			</select>
		</div>
	</div>		

<div class="clear">
	<div class="input">
		<input id="btnEditStatus" type="button" value="Save" />
		<input id="btnCancelStatus" class="right" type="button" value="Cancel" />
	</div>
</div>
	
	
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');