<?php

include_once TEMPLATE_PATH.'/site/helper/formatEvents.php';
include_once TEMPLATE_PATH.'/site/helper/format.php';

$events = $SOUP->get('events', array());
$size = $SOUP->get('size', 'large');
$olderURL = $SOUP->get('olderURL', null);
$title = $SOUP->get('title', 'Recent Activity');
$showProject = $SOUP->get('showProject', false);
$page = $SOUP->get('page');
$numPages = $SOUP->get('numPages');

$thisURL = Url::admin();

$fork = $SOUP->fork();
$fork->set('id', 'activity');
$fork->set('title', $title);

$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){

	$('#activity div.diff-box').dialog({
		autoOpen: false,
		title: 'Activity Details',
		modal: true,
		width: 500
	});

	$('#activity a.diff').click(function(){
		var id = $(this).attr('id').substring(5);
		$('#diff-box-'+id).dialog('open');
		return false;
	});
	
	$('#selActivityPages').change(function(){
		var url = '<?= $thisURL ?>';
		var pageNum = $(this).val();
		window.location = url+'/'+pageNum;
	});
    
});

</script>

<?php

if($events != null)
{
	echo '<ul class="segmented-list activity">';
	foreach($events as $event)
	{
		echo '<li class="'.$event->getCssClass().'">';
		echo '<h6 class="primary">'.formatEvent($event, $showProject).'</h6>';
		echo '<p class="secondary">'.formatTimeTag($event->getDateCreated());
		$details = formatEventDetails($event);
		if(!empty($details)) {
			echo ' <span class="slash">/</span> <a id="diff-'.$event->getID().'" class="diff" href="#">Details</a></p>';
			// diff box
			echo '<div id="diff-box-'.$event->getID().'" class="diff-box">';
				echo formatEvent($event, $showProject).' ('.formatTimeTag($event->getDateCreated()).')';
				echo '<div class="line"> </div>';
				echo $details;
			echo '</div>';
		} else {
			echo '</p>';
		}
		echo '</li>';
	}
	echo '</ul>';

} else {
	echo "<p>(none)</p>";
}


$fork->endBlockSet();

if($olderURL != null):

	$fork->startBlockSet("footer");
    echo '<p><a href="'.$olderURL.'">Older Activity &raquo;</a></p>';
	$fork->endBlockSet();

elseif($numPages > 1):

	$fork->startBlockSet("footer");

	if($page != 1) {
		$newerURL = $thisURL.'/'.($page-1);
		echo '<a href="'.$newerURL.'">&laquo; Newer</a> ';
	}
    
    echo '<select id="selActivityPages">';
    
	for($i=1; $i<=$numPages; $i++) {
		$selected = ($i == $page) ? ' selected="selected"' : '';
		echo '<option value="'.$i.'"'.$selected.'>page '.$i.'</option>';
	}

    echo '</select>';

	if($page != $numPages) {
		$olderURL = $thisURL.'/'.($page+1);
		echo ' <a href="'.$olderURL.'">Older &raquo;</a>';
	}

    $fork->endblockSet();

endif;

$fork->render('site/partial/panel');