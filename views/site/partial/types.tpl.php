<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';


$hasPermission = Session::isAdmin();

$types = $SOUP->get('types');

$fork = $SOUP->fork();

$fork->set('id', 'types');
$fork->set('title', "Document Types");
$fork->set('editable', $hasPermission);
$fork->set('editLabel', "Add a New Type");
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
	
	$("#types .editButton").click(function() {
		$(this).hide();
		$("#types .view").hide();
		$("#types .edit").fadeIn();			
	});
	
	$("#btnCancelTypes").click(function(){
		$("#types .edit").hide();
		$("#types .view").fadeIn();
		$("#types .editButton").fadeIn();
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

</div><!-- .edit -->

<?php endif; ?>




<div class="view">

<ul class="segmented-list">
        <?php
            foreach ($types as $type) {
                print("<li><strong>".$type['typeName']."</strong> - ".$type['description']."</li>");
            }
        ?>
</ul>

</div><!-- .view -->

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');