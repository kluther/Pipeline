<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$task = $SOUP->get('task');
$project = $SOUP->get('project');
$leader = User::load($task->getLeaderID());
$comments = $SOUP->get('comments', array());

// only admin or trusted may edit
$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

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
	
	// $('#btnEditTask').click(function(){
		// buildPost({
			// 'processPage':'<?= Url::taskProcess($task->getID()) ?>',
			// 'info': $('#frmEditItem').serialize(),
			// 'buttonID':'#btnEditTask'
		// });
	// });
	
	$("#task .editButton").click(function(){
		$(this).hide();
		$("#task .view").hide();
		$("#task .edit").fadeIn();
		initializeUploader();
		$('#txtTitle').focus();			
	});
	
	$("#btnCancelTask").click(function(){
		$("#task .edit").hide();
		$("#task .view").fadeIn();
		$("#task .editButton").fadeIn();
	});	
});

function uploadComplete(){
	buildPost({
		'processPage':'<?= Url::taskProcess($task->getID()) ?>',
		'info': $('#frmEditItem').serialize(),
		'buttonID':'#btnEditTask'
	});
}
	
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
		<p>A <a href="<?= Url::help() ?>">trusted member</a> to lead this task</p>
	</div>
</div>

<div class="clear">
	<label for="txtDescription">Instructions<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtDescription" name="txtDescription"><?= $task->getDescription() ?></textarea>
		<p><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
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
	<label>Attached Files</label>
	<div class="input">
		<input type="button" id="btnSelectFiles" value="Add Files" />
		<p>Max size <?= MAX_UPLOAD_SIZE ?> MB each</p>
		<div id="filelist"></div>
		<?php 
			$SOUP->render('project/partial/editUploads',array(
			));
		?>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnEditTask" type="button" value="Save" />
		<input id="btnCancelTask" type="button" value="Cancel" />
	</div>
</div>

</form>

<?php
	$SOUP->render('site/partial/newUpload', array(
		'uploadButtonID' => 'btnEditTask',
		'formID' => 'frmEditItem'
	));
?>

</div><!-- end .edit -->

<?php endif; ?>

<div class="view">

<div class="person-box">
	<?= formatUserPicture($task->getLeaderID(), 'small') ?>
	<div class="text">
		<p class="caption">task leader</p>
		<p class="username"><?= formatUserLink($task->getLeaderID(), $project->getID()) ?></p>
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

<h5><?= $task->getTitle() ?></h5>

<p><?= $status ?> <span class="slash">/</span> <?= ($task->getDeadline() != '') ? 'due '.formatTimeTag($task->getDeadline()) : 'no deadline' ?></p>

<div class="line"></div>

<p><?= formatTaskDescription($task->getDescription()) ?></p>

<?php
	$SOUP->render('site/partial/newUploads', array(
	//	'uploads' => $uploads
	));
?>

</div><!-- end .view -->

<?php
	$SOUP->render('project/partial/comments', array(
		'comments' => $comments,
		'processURL' => Url::taskProcess($task->getID()),
		'parentID' => $task->getID(),
		'size' => 'large',
		//'class' => 'hidden',
		'id' => 'comments'
		));
?>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
