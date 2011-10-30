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
$user = $SOUP->get('user', null);

// allow values to be passed in
if($hasPermission === null) {
	// only organizers or creator may create tasks
	$hasPermission = ( Session::isAdmin() ||
						$project->isTrusted(Session::getUserID()) ||
						$project->isCreator(Session::getUserID()) );
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
if(!empty($tasks)) {
	if($size == 'small') {
		echo '<ul class="segmented-list tasks">';
		foreach($tasks as $t) {
			echo '<li>';
			// title
			echo '<h6 class="primary"><a href="'.Url::task($t->getID()).'">'.$t->getTitle().'</a></h6>';
			echo '<p class="secondary">';
 			// status
			if($t->getStatus() == Task::STATUS_OPEN) {
				echo '<span class="good">open</span>';
			} else {
				echo '<span class="bad">closed</span>';
			}
			echo ' <span class="slash">/</span> ';
			// deadline
			$deadline = $t->getDeadline();
			$deadline = (empty($deadline)) ? 'no deadline' : 'due '.formatTimeTag($deadline);
			echo $deadline;
			echo ' <span class="slash">/</span> ';
			// num needed
			$numNeeded = $t->getNumNeeded();
			$numAccepted = $t->getNumAccepted();
			if(empty($numNeeded)) {
				$stillNeeded = '&#8734;';
			} elseif($numNeeded > $numAccepted) {
				$stillNeeded = $numNeeded - $numAccepted;
			} else {
				$stillNeeded = 0;
			}
			echo $stillNeeded.' people needed';
			echo '</p>';
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<table class="tasks">';
		// table heading
		echo '<tr>';
		echo '	<th style="padding-left: 22px;">Task</th>';
		echo '	<th>Status</th>';
		echo '	<th>Deadline</th>';
		echo '	<th>Needed</th>';
		if(!is_null($user)) {
			echo '	<th>Role</th>';
		}
		echo '</tr>';
		foreach($tasks as $t) {
			echo '<tr>';
			// title
			echo '<td class="name">';
			echo '<h6><a href="'.Url::task($t->getID()).'">'.$t->getTitle().'</a></h6>';
			if(is_null($project)) {
				// project
				$ptitle = Project::load($t->getProjectID())->getTitle();
				echo '<p>in <a href="'.Url::project($t->getProjectID()).'">'.$ptitle.'</a></p>';
			} else {
				// description
				echo '<p>';
				echo substr($t->getDescription(),0,70);
				if(strlen($t->getDescription()) > 70)
					echo '...';
				echo '</p>';			
			}
			echo '</td>';
			// status
			if($t->getStatus() == Task::STATUS_OPEN) {
				echo '<td class="status good">open</td>';
			} else {
				echo '<td class="status bad">closed</td>';
			}
			// deadline
			$deadline = $t->getDeadline();
			$deadline = (empty($deadline)) ? '--' : formatTimeTag($deadline);
			echo '<td class="deadline">'.$deadline.'</td>';
			// num needed
			$numNeeded = $t->getNumNeeded();
			$numAccepted = $t->getNumAccepted();
			if(empty($numNeeded)) {
				$stillNeeded = '&#8734;';
			} elseif($numNeeded > $numAccepted) {
				$stillNeeded = $numNeeded - $numAccepted;
			} else {
				$stillNeeded = 0;
			}
			echo '<td class="needed">'.$stillNeeded.'</td>';
			// role
			if(!is_null($user)) {
				// relationship to task
				if($user->getID() == $t->getLeaderID()) {
					echo '<td class="role">leading</td>';
				} else {
					$accepted = Accepted::getByUserID($user->getID(), $t->getID());
					if(!empty($accepted)) {
						$role = Accepted::getStatusName($accepted->getStatus());
						echo '<td class="role">'.$role.'</td>';
					}
				}
			}
			echo '</tr>';
		}
		echo '</table>';
	}
} else {
	echo "<p>(none)</p>";
}

$fork->endBlockSet();
$fork->render('site/partial/panel');