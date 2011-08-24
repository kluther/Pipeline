<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$tasks = $SOUP->get('tasks', array());
$title = $SOUP->get('title', 'Tasks');
$project = $SOUP->get('project');
$creatable = $SOUP->get('creatable', true);
$id = $SOUP->get('id', 'tasks');
$size = $SOUP->get('size', 'large');
$showRelationship = $SOUP->get('showRelationship', true);

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('creatable', $creatable);

if($size == 'small') {
	$fork->set('createLabel', 'New');
} else {
	$fork->set('createLabel', 'New Task');
}

$fork->startBlockSet('body');
?>


<script type="text/javascript">

	$(document).ready(function() {
		$('#<?= $id ?> .createButton').click(function() {
			window.location = '<?= Url::taskNew($project->getID()) ?>';
		});
	});

</script>

<?php
if($tasks != null) {
	echo '<ul class="segmented-list tasks">';
	foreach($tasks as $task) {
		echo '<li>';
		
		// title
		$title = $task->getTitle();
		$url = Url::task($task->getID());
		$closed = ($task->getStatus() == Task::STATUS_CLOSED) ? ' closed' : ''; // CSS class for strikethrough
		echo '<h6 class="primary'.$closed.'"><a href="'.$url.'">'.$title.'</a>';
		
		// status
		if($task->getStatus() == Task::STATUS_CLOSED)
			echo '&nbsp;<span class="status bad">closed</span>';
		else
			echo '&nbsp;<span class="status good">open</span>';
		
		echo '</h6>'; // .primary
		
		echo '<p class="secondary">';
		
		// deadline
		if($task->getDeadline() != '')
			echo 'due '.formatTimeTag($task->getDeadline());
		else
			echo 'no deadline';
		echo ' <span class="slash">/</span> ';			
		
		// num needed
		$numNeeded = $task->getNumNeeded();
		if($numNeeded == 0)
			echo '&#8734; needed';
		else
			echo $numNeeded.' needed';
		echo ' <span class="slash">/</span> ';	
		
		// num accepted
		$numAccepted = $task->getNumAccepted();
		echo $numAccepted.' joined';
			
		echo '</p>'; // .secondary
		echo '</li>';
		}
	echo '</ul>';
	} else {
	echo "<p>(none)</p>";
	}

$fork->endBlockSet();
$fork->render('site/partial/panel');