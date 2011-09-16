<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

// supported
// - user's dashboard tasks YES USER, NO PROJECT
// - user's own tasks within a project YES USER, YES PROJECT
// - user's tasks on their profile YES USER, NO PROJECT

$project = $SOUP->get('project', null);
$user = $SOUP->get('user'); // can't be null
$tasks = $SOUP->get('tasks');
$id = $SOUP->get('id', 'tasks');
$size = $SOUP->get('size', 'large');
$title = $SOUP->get('title', 'Tasks');
$hasPermission = $SOUP->get('hasPermission', null);

// allow values to be passed in
if($hasPermission === null) {
	// only organizers or creator may create tasks
	$hasPermission = ( Session::isAdmin() ||
						ProjectUser::isOrganizer(Session::getUserID(), $project->getID()) ||
						ProjectUser::isCreator(Session::getUserID(), $project->getID()) );
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
		
		// relationship to task
		$relationship = '';
		if($user->getID() == $task->getLeaderID()) {
			$relationship = 'task leader';
		} else {
			$accepted = Accepted::getByUserID($user->getID(), $task->getID());
			if($accepted != null) {
				$relationship = Accepted::getStatusName($accepted->getStatus());
			}
		}
		echo '&nbsp;<span class="status">'.$relationship.'</span>';
		
		echo '</h6>'; // .primary
		
		echo '<p class="secondary">';
		
		// project
		if($project === null) {
			echo 'in '.formatProjectLink($task->getProjectID());
			echo ' <span class="slash">/</span> ';	
		}
		
		// status
		if($task->getStatus() == Task::STATUS_CLOSED)
			echo '<span class="status bad">closed</span>';
		else
			echo '<span class="status good">open</span>';
		echo ' <span class="slash">/</span> ';	
		
		// deadline
		if($task->getDeadline() != '')
			echo 'due '.formatTimeTag($task->getDeadline());
		else
			echo 'no deadline';		
		
		}
	echo '</ul>';
	} else {
	echo "<p>(none)</p>";
	}

$fork->endBlockSet();
$fork->render('site/partial/panel');