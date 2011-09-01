<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$user = $SOUP->get('user', null);
$tasks = $SOUP->get('tasks', array());
$title = $SOUP->get('title', 'Tasks');
$id = $SOUP->get('id', 'tasks');
$size = $SOUP->get('size', 'large');

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('id', $id);

$fork->startBlockSet('body');

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
		echo 'in '.formatProjectLink($task->getProjectID());
		echo ' <span class="slash">/</span> ';	
		
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