<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$task = $SOUP->get('task');
$project = $SOUP->get('project');
$leader = User::load($task->getLeaderID());

// only admin or trusted may edit
$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) );

$fork = $SOUP->fork();

$fork->set('id', 'task');
$fork->set('editable', $hasPermission);
$fork->set('editLabel', 'Edit');
$fork->set('title', 'Task Info');

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
	
	$('#txtLeader').autocomplete({
		source: '<?= Url::peopleSearch($project->getID()) ?>/trusted',
		minLength: 2
	});
	
	$('#selStatus').val('<?= $task->getStatus() ?>');
	
	$('#btnEditTask').click(function(){
		buildPost({
			'processPage':'<?= Url::taskProcess($task->getID()) ?>',
			'info': $('#frmEditItem').serialize(),
			'buttonID':'#btnEditTask'
		});
	});
	
	$("#task .editButton").click(function(){
		$(this).hide();
		$("#task .view").hide();
		$("#task .edit").fadeIn();
		$('#txtTitle').focus();			
	});
	
	$("#btnCancelTask").click(function(){
		$("#task .edit").hide();
		$("#task .view").fadeIn();
		$("#task .editButton").fadeIn();
	});	
});
</script>


<div class="edit hidden">

<form id="frmEditItem">

<input type="hidden" name="action" value="edit" />

<div class="clear">
	<label for="txtTitle">Title<span class="required">*</span></label>
	<div class="input">
		<input id="txtTitle" name="txtTitle" type="text" maxlength="255" value="<?= $task->getTitle() ?>" />
		<p>Short description of this task</p>
	</div>
</div>

<div class="clear">
	<label for="txtLeader">Leader<span class="required">*</span></label>
	<div class="input">
		<input id="txtLeader" name="txtLeader" type="text" value="<?= $leader->getUsername() ?>" />
		<p>A <a href="<?= Url::help() ?>">trusted user</a> to lead this task</p>
	</div>
</div>

<div class="clear">
	<label for="txtDescription">Instructions<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtDescription" name="txtDescription"><?= $task->getDescription() ?></textarea>
	</div>
</div>

<div class="clear">
	<label for="selStatus">Status<span class="required">*</span></label>
	<div class="input">
		<select id="selStatus" name="selStatus">
			<option value="<?= Task::STATUS_OPEN ?>"><?= Task::getStatusName(Task::STATUS_OPEN) ?></option>
			<option value="<?= Task::STATUS_CLOSED ?>"><?= Task::getStatusName(Task::STATUS_CLOSED) ?></option>
		</select>
	</div>
</div>

<div class="clear">
	<label for="txtNumNeeded"># People Needed</label>
	<div class="input">
		<input id="txtNumNeeded" name="txtNumNeeded" type="text" value="<?= $task->getNumNeeded() ?>" />
		<p>Number of people needed for this task<br />
		(Leave empty for unlimited)</p>
	</div>
</div>

<div class="clear">
	<label for="txtDeadline">Deadline</label>
	<div class="input">
		<input id="txtDeadline" name="txtDeadline" type="text" value="<?= ($task->getDeadline() != '') ? date("Y-m-d",strtotime($task->getDeadline())) : '' ?>" />
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnEditTask" type="button" value="Save" />
		<input id="btnCancelTask" type="button" value="Cancel" />
	</div>
</div>

</form>

</div><!-- end .edit -->

<?php endif; ?>

<div class="view">

<div class="person-box">
	<a class="picture large" href="<?= Url::user($task->getLeaderID()) ?>"><img src="<?= Url::userPictureLarge($task->getLeaderID()) ?>" /></a>
	<div class="text">
		<p class="caption">task leader</p>
		<p class="username"><?= formatUserLink($task->getLeaderID()) ?></p>
	</div>
</div>

<?php

if($task->getStatus() == Task::STATUS_OPEN) {
	$status = '<span class="status good">open</span>';
} else {
	$status = '<span class="status bad">closed</span>';
}

$closed = ($task->getStatus() == Task::STATUS_CLOSED) ? ' class="closed"' : ''; // CSS class for strikethrough
?>

<h5<?= $closed ?>><a href="<?= Url::task($task->getID()) ?>"><?= $task->getTitle() ?></a></h5>

<p><?= $status ?> <span class="slash">/</span> <?= ($task->getDeadline() != '') ? 'due '.formatTimeTag($task->getDeadline()) : 'no deadline' ?></p>

<div class="line"></div>

<p><?= formatTaskDescription($task->getDescription()) ?></p>

</div><!-- end .view -->


<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');