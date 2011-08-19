<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$tasks = $SOUP->get('tasks', array());
$showRelationship = $SOUP->get('showRelationship', true);

$fork = $SOUP->fork();

$fork->startBlockSet('body');

if($tasks != null) {
	echo '<ul class="segmented-list tasks">';
	foreach($tasks as $task) {
		echo '<li>';
		
		// relationship
		if($showRelationship == true) {
			if(Session::getUserID() == $task->getLeaderID())
				echo '<p class="relationship">leader</p>';
			else {
				$accepted = Accepted::getByUserID(Session::getUserID(), $task->getID());
				if($accepted != null)
					echo '<p class="relationship">'.Accepted::getStatusName($accepted->getStatus()).'</p>';
			}
		}
		
		// title
		$title = $task->getTitle();
		$url = Url::task($task->getID());
		$closed = ($task->getStatus() == Task::STATUS_CLOSED) ? ' closed' : ''; // CSS class for strikethrough
		echo '<p class="title'.$closed.'"><a href="'.$url.'">'.$title.'</a></p>';
		
		echo '<p class="info">';
		
		// status
		$statusName = Task::getStatusName($task->getStatus());
		if($task->getStatus() == Task::STATUS_CLOSED)
			echo '<span class="bad">'.$statusName.'</span>';
		else
			echo '<span class="good">'.$statusName.'</span>';

		// deadline
		if($task->getDeadline() != '')
			echo ' <span class="slash">/</span> due '.formatTimeTag($task->getDeadline());
		
		// num accepted
		echo ' <span class="slash">/</span> ';
		$numAccepted = $task->getNumAccepted();
		$numNeeded = $task->getNumNeeded();
		//echo 'accepted by '.formatCount($numAccepted, 'person', 'people').' ';
		if($numNeeded == 0)
			echo '&#8734; people needed';
		elseif($numNeeded > $numAccepted) {
			$needed = $numNeeded - $numAccepted;
			echo formatCount($needed, 'person', 'people').' needed';
			} else {
			echo formatCount($numAccepted, 'person', 'people').' joined';
			//} elseif($numNeeded < $numAccepted) {
			//$extra = $numAccepted - $numNeeded;
			//echo '<span class="good">('.$extra.'&nbsp;extra)</span>';
			}		
			
		echo '</p>';
		echo '</li>';
		}
	echo '</ul>';
	} else {
	echo "<p>(none)</p>";
	}

$fork->endBlockSet();
$fork->render('site/partial/panel');