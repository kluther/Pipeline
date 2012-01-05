<?php

$uploads = $SOUP->get('uploads');
$page = $SOUP->get('page');
$numPages = $SOUP->get('numPages');
$project = $SOUP->get('project');
$totalNumUploads = $SOUP->get('totalNumUploads', 0);

$thisURL = Url::files($project->getID());

$fork = $SOUP->fork();
$fork->set('title', 'Files Attached to Tasks ('.$totalNumUploads.')');
$fork->startBlockSet('body');

?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#selTaskFilesPages').change(function(){
			var url = '<?= $thisURL ?>';
			var pageNum = $(this).val();
			window.location = url+'/'+pageNum;
		});
	});
</script>

<?php
	if(empty($uploads)) {
		echo '<p>None yet. To upload files, <a href="'.Url::taskNew($project->getID()).'">create a task</a> or contribute to an <a href="'.Url::tasks($project->getID()).'">existing task</a>.</p>';
	} else {
		$SOUP->render('site/partial/newUploads', array(
			'showParent' => true
		));
	}
?>

<?php

$fork->endBlockSet();

// pagination, if necessary
if($numPages > 1) {
	$fork->startBlockSet('footer');

	if($page != 1) {
		$olderURL = $thisURL;
		$olderURL .= '/'.($page-1);
		echo '<a href="'.$olderURL.'">&laquo; Previous</a> ';
	}

	echo '<select id="selTaskFilesPages">';
	for($i=1; $i<=$numPages; $i++) {
		$selected = ($i == $page) ? ' selected="selected"' : '';
		echo '<option value="'.$i.'"'.$selected.'>page '.$i.'</option>';
	}
	echo '</select>';

	if($page != $numPages) {
		$newerURL = $thisURL;
		$newerURL .= '/'.($page+1);
		echo ' <a href="'.$newerURL.'">Next &raquo;</a>';
	}

	$fork->endBlockSet();
}

$fork->render('site/partial/panel');