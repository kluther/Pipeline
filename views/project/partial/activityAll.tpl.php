<?php

include_once TEMPLATE_PATH.'/site/helper/formatEvents.php';
include_once TEMPLATE_PATH.'/site/helper/format.php';

$events = $SOUP->get('events');
$page = $SOUP->get('page');
$numPages = $SOUP->get('numPages');
$project = $SOUP->get('project');
$filter = $SOUP->get('filter');

$thisURL = Url::activity($project->getID());
$thisFilteredURL = $thisURL;
if( !empty($filter) && ($filter != 'all') )
	$thisFilteredURL .= '/'.$filter;

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#selActivityFilter').val('<?= $filter ?>');
	
	$('#selActivityFilter').change(function(){
		var url = getURL();
		window.location = url;
	});

	$('#selActivityPages').change(function(){
		var url = getURL();
		var pageNum = $(this).val();
		window.location = url+'/'+pageNum;
	});
});

function getURL() {
	var url = '<?= $thisURL ?>';
	var filter = $('#selActivityFilter').val();
	if(filter != '')
		url = url+'/'+filter;
	return url;
}

</script>

<div class="panel large">
	<div class="panel-header">
		<h4>Activity</h4>
		<div class="activity-filter">
			<select id="selActivityFilter">
				<option value="">show all activity</option>
				<option value="basics">Basics only</option>
				<option value="tasks">Tasks only</option>
				<option value="discussions">Discussions only</option>
				<option value="people">People only</option>
			</select>
		</div>
	</div>
	<div class="panel-body">

<?php

if(!empty($events))
{
	echo '<ul class="segmented-list activity">';
	foreach($events as $event)
	{
		echo '<li class="'.$event->getCssClass().'">';
		echo '<h6 class="primary">'.formatEvent($event, $showProject).'</h6>';
		echo '<p class="secondary">'.formatTimeTag($event->getDateCreated()).'</p>';
		$details = formatEventDetails($event);
		if(!empty($details)) {
			echo '<blockquote class="details">'.$details.'</blockquote>';
		}
		echo '</li>';
	}
	echo '</ul>';

} else {
	echo "<p>(none)</p>";
}

?>
	</div>
	<div class="panel-footer">
<?php
	if($page != 1) {
		$newerURL = $thisFilteredURL;
		$newerURL .= '/'.($page-1);
		echo '<a href="'.$newerURL.'">&laquo; Newer</a> ';
	}
?>
		<select id="selActivityPages">
<?php
	for($i=1; $i<=$numPages; $i++) {
		$selected = ($i == $page) ? ' selected="selected"' : '';
		echo '<option value="'.$i.'"'.$selected.'>page '.$i.'</option>';
	}
?>
		</select>
<?php
	if($page != $numPages) {
		$olderURL = $thisFilteredURL;
		$olderURL .= '/'.($page+1);
		echo '<a href="'.$olderURL.'">Older &raquo;</a> ';
	}
?>
	</div>
</div>