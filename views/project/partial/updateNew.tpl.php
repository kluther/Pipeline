<?php

$project = $SOUP->get('project');
$task = $SOUP->get('task');
//$accepted = $SOUP->get('accepted');
//$token = Upload::generateToken();

$fork = $SOUP->fork();
$fork->set('title', 'New Update');
$fork->startBlockSet('body');

?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#txtTitle').focus();
		$('#btnCreateUpdate').click(function(){
			buildPost({
				'processPage':'<?= Url::updateNewProcess($task->getID()) ?>',
				'info':{
					'action': 'create',
					'message': $('#txtMessage').val(),
				},
				'buttonID':'#btnCreateUpdate'
			});
		});
	});
</script>

<div class="clear">
	<label for="txtTitle">Title<span class="required">*</span></label>
	<div class="input">
		<input type="text" id="txtTitle" name="txtTitle" maxlength="255" />
		<p>Short title for the update</p>
	</div>
</div>

<div class="clear">
	<label for="txtMessage">Message<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtMessage" name="txtMessage"></textarea>
		<p>Write your update here</p>
	</div>
</div>


<div class="clear">
	<label>Uploads</label>
	<div class="input">
		
	<?php
		$SOUP->render('site/partial/newUpload', array(
		
		));
	?>
		
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnCreateUpdate" type="button" value="Create Update" />
	</div>
</div>


<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');