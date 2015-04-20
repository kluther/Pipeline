<?php

$project = $SOUP->get('project');

$fork = $SOUP->fork();
$fork->set('title', 'New Reflection');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#txtTitle').focus();
	$('#btnCreateReflection').click(function(){
		buildPost({
			'processPage':'<?= Url::discussionNewProcess($project->getID()) ?>',
			'info':{
				'action':'create-reflection',
				'title':$('#txtTitle').val(),
				'message':$('#txtMessage').val(),
				'visibility':$('#selVisibility').val()
			},
			'buttonID':'#btnCreateReflection'
		});
	});	
});

</script>

<div class="clear">
	<label for="txtTitle">Title<span class="required">*</span></label>
	<div class="input">
		<input id="txtTitle" type="text" maxlength="255" />
	</div>
</div>

<div class="clear">
	<label for="txtMessage">Reflection<span class="required">*</span></label>
	<div class="input">
		<textarea id="txtMessage"></textarea>
		<p><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
	</div>
</div>

<div class="clear">
	<label for="selVisibility">Visibility</label>
	<div class="input">
		<select id="selVisibility">
			<option value="<?= Discussion::REFLECT_VIS_ME ?>">Me only</option>
            <option value="<?= Discussion::REFLECT_VIS_ME_INSTR ?>">Me + instructors</option>
            <option value="<?= Discussion::REFLECT_VIS_ME_INSTR_PROJ_MEMB ?>" selected="selected">Me + instructors + project members</option>
            <option value="<?= Discussion::REFLECT_VIS_EVERYONE ?>">Everyone</option>
		</select>
		<p>Who can see this reflection</p>
	</div>
</div>

<div class="clear">
	<div class="input">
		<input id="btnCreateReflection" type="button" value="Post Reflection" />
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');