<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';


$hasPermission = Session::isAdmin();

//$types = $SOUP->get('documents');

$fork = $SOUP->fork();

$fork->set('id', 'document');
$fork->set('title', "Document");
$fork->set('editable', $hasPermission);
$fork->set('editLabel', "Change the Current Document");
$fork->set('createable', $hasPermission);
$fork->set('createLabel', "Add a New Document");
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript">
$(document).ready(function(){
        
	$('#btnEditProgress').click(function(){
		buildPost({
			'processPage':'<?= Url::adminSettingsProcess() ?>',
			'info': {
				'action':'progress',
                                
			},
			'buttonID':'#btnEditProgress'
		});
	});	
	
	$("#document .editButton").click(function() {
		$(this).hide();
		$("#document .view").hide();
		$("#document .edit").fadeIn();			
	});
	
	$("#btnCancelDocument").click(function(){
		$("#document .edit").hide();
		$("#document .view").fadeIn();
		$("#document .editButton").fadeIn();
	});		
});
</script>

<div class="edit hidden">

<!--Controls Status drop-down list-->
<div class="clear">
	<label for="selStatus">Status<span class="required">*</span></label>
	<div class="input">
            
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
		<input id="btnCancelDocument" type="button" value="Cancel" />
	</div>
</div>

</div><!-- .edit -->

<?php endif; ?>




<div class="view">

<ul class="segmented-list">
        <?php
//            foreach ($types as $type) {
//                print("<li><strong>".$type['typeName']."</strong> - ".$type['description']."</li>");
//            }
        ?>
</ul>

</div><!-- .view -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');