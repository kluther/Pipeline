<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

// admin, trusted, creator may edit
$hasPermission = ( Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

$formattedPitch = ($project->getPitch() != '') ? formatPitch($project->getPitch()) : '(none)';					
					
$fork = $SOUP->fork();
$fork->set('title', "Pitch");
$fork->set('id', "pitch");
$fork->set('editable', $hasPermission);
//$fork->set('editLabel', 'Edit Pitch');
$fork->startBlockSet('body');

?>

<?php if($hasPermission): ?>

<script type="text/javascript">

$(document).ready(function(){
	$("#btnSavePitch").mousedown(function(){
		buildPost({
			'processPage':'<?= Url::detailsProcess($project->getID()) ?>',
			'info':
			{
				'action':'pitch',
				'pitch':$("#txtPitch").val()
			},
			'buttonID':'#btnSavePitch'
		});
	});

	$("#pitch .editButton").click(function(){
		$(this).hide();
		$("#pitch .view").hide();
		$("#pitch .edit").fadeIn();
		$('#txtPitch').focus();			
	});
	
	$("#btnCancelPitch").click(function(){
		$("#pitch .edit").hide();
		$("#pitch .view").fadeIn();
		$("#pitch .editButton").fadeIn();
	});	
});

</script>

<div class="edit hidden">

<div class="clear">
	<label for="txtPitch">Pitch<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtPitch"><?= $project->getPitch() ?></textarea>
		<p>Allowed tags: &lt;a&gt; &lt;strong&gt; &lt;b&gt; &lt;em&gt; &lt;i&gt;</p>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnSavePitch" type="button" value="Save" />
		<input id="btnCancelPitch" type="button" value="Cancel" />
	</div>
</div>

</div><!-- .edit -->

<?php endif; ?>

<div class="view">

<?= $formattedPitch ?>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
