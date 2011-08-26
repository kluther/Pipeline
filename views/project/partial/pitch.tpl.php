<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');

$fork = $SOUP->fork();
$fork->set('title', "Pitch");
$fork->set('id', "pitch");
$fork->set('editable', true);
$fork->set('editLabel', 'Edit Pitch');
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

<textarea id="txtPitch"><?= html_entity_decode($project->getPitch()) ?></textarea>

<div class="buttons">
	<input id="btnSavePitch" class="right" type="button" value="Save" />
	<input id="btnCancelPitch" class="right" type="button" value="Cancel" />
	<p class="right">Allowed tags: &lt;a&gt; &lt;strong&gt; &lt;b&gt; &lt;em&gt; &lt;i&gt;</p>
</div>

</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');
