<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

// supported:
// - other tasks within project NO USER, YES PROJECT
// - list of open tasks in 'find projects' NO USER, NO PROJECT
// - single task next to update - NO USER, YES PROJECT

$tasks = $SOUP->get('tasks', array());
$title = $SOUP->get('title', 'Tasks');
$project = $SOUP->get('project', null);
$id = $SOUP->get('id', 'tasks');
$size = $SOUP->get('size', 'large');
$hasPermission = $SOUP->get('hasPermission', null);

// allow values to be passed in
if($hasPermission === null) {
	// only organizers or creator may create tasks
	$hasPermission = ( Session::isAdmin() ||
						$project->isTrusted(Session::getUserID()) );
}

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('id', $id);
$fork->set('creatable', $hasPermission);
$fork->set('createLabel', 'New Task');
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript">

	$(document).ready(function() {
		$('#<?= $id ?> .createButton').click(function() {
			window.location = '<?= Url::taskNew($project->getID()) ?>';
		});
	});

</script>

<?php endif; ?>

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
		$numAccepted = $task->getNumAccepted();
		$numNeeded = $task->getNumNeeded();
		
		if($numNeeded == 0) {
			echo ($size != 'small') ? '&#8734; people needed' : '&#8734; needed';
		} else {
			$numNeeded -= $numAccepted;
			if($size != 'small')
				$numNeeded = formatCount($numNeeded,'person','people');
			echo $numNeeded.' needed';
		}
		echo ' <span class="slash">/</span> ';	
		
		// num accepted
		if($size != 'small')
			$numAccepted = formatCount($numAccepted,'person','people');
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