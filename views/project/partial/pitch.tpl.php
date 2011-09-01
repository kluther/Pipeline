<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$fork = $SOUP->fork();
$fork->set('title', "Pitch");
$fork->set('id', "pitch");
$fork->set('editable', true);
//$fork->set('editLabel', 'Edit Pitch');
$fork->startBlockSet('body');

$formattedPitch = formatPitch($project->getPitch());

?>

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
	$("#btnCancelPitch").mousedown(function(){
		$("#pitch .edit").hide();
		$("#pitch .view").fadeIn();
	});
	$("#pitch .editButton").click(function(){
		var edit = $("#pitch .edit");
		var view = $("#pitch .view");
		toggleEditView(edit, view);
		if($(view).is(":hidden"))
			$('#txtPitch').focus();
	});
});



</script>

<div class="view">

<?= $formattedPitch ?>

</div>

<div class="edit hidden">

<div class="clear">
	<label for="txtPitch">Pitch<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtPitch"><?= html_entity_decode($project->getPitch()) ?></textarea>
		<p>Allowed tags: &lt;a&gt; &lt;strong&gt; &lt;b&gt; &lt;em&gt; &lt;i&gt;</p>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnSavePitch" type="button" value="Save" />
		<input id="btnCancelPitch" type="button" value="Cancel" />
	</div>
</div>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
