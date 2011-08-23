<?php
require_once('./../../global.php');

$slug = Filter::text($_GET['slug']);
	
// check project
$project = Project::getProjectFromSlug($slug);
if($project == null) {
	Session::setMessage('That project does not exist.');
	header('Location: '.Url::error());
	exit();
}

$action = Filter::text($_POST['action']);

if ( ($action == 'create') || ($action == 'edit') ) {
	//$token = Filter::alphanum($_POST['token']);
	$title = Filter::text($_POST['txtTitle']);
	$leaderID = Filter::numeric($_POST['txtLeader']);
	$description = Filter::text($_POST['txtDescription']);
	$status = Filter::numeric($_POST['selStatus']);
	$numNeeded = Filter::numeric($_POST['txtNumNeeded']);
	$deadline = Filter::text($_POST['txtDeadline']);

	// validate the data
	
	// required fields
	if($title == '') {
		$json = array('error' => 'You must provide a name for this task.');
		exit(json_encode($json));
	} elseif($leaderID == '') {
		$json = array('error' => 'This task must have a leader.');
		exit(json_encode($json));		
	} elseif($description == '') {
		$json = array('error' => 'You must provide some instructions for this task.');
		exit(json_encode($json));
	}
	
	// num needed must be numeric or empty
	if( ($numNeeded != '') && (!is_numeric($numNeeded)) ) {
		$json = array('error' => 'Number of people needed must be a valid number or empty (for unlimited).');
		exit(json_encode($json));
	}
	
	// check for valid date
	$formattedDeadline = strtotime($deadline);
	if( ($formattedDeadline === false) && ($deadline != '') ) {
		$json = array('error' => 'Deadline must be a valid date or empty.');
		exit(json_encode($json));
	}
}

if ( ($action == 'edit') || ($action == 'accept') || ($action == 'comment') || ($action == 'comment-reply') ) {
	// instantiate and validate task
	$taskID = Filter::numeric($_GET['t']);
	$task = Task::load($taskID);
	
	if($task == null) {
		$json = array('error' => 'That task does not exist.');
		exit(json_encode($json));	
	}
}

if($action == 'create') {
	// create task
	// first the required stuff
	$task = new Task(array(
		'creator_id' => Session::getUserID(),		
		'leader_id' => $leaderID,
		'project_id' => $project->getID(),
		'title' => $title,
		'description' => $description,
		'status' => $status
	));
	// now the optional stuff
	if($formattedDeadline !== false) {
		$formattedDeadline = date("Y-m-d H:i:s", $formattedDeadline);
		$task->setDeadline($formattedDeadline);
	}
	if($numNeeded != '')
		$task->setNumNeeded($numNeeded);
	$task->save();
	
	// save uploaded files to database
	foreach($_POST['file'] as $stored => $orig) {
		$stored = Filter::text($stored);
		$orig = Filter::text($orig);
		Upload::saveToDatabase(
			$orig,
			$stored,
			Upload::TYPE_TASK,
			$task->getID(),
			$project->getID()
			);
	}
	
	// log it
	$logEvent = new Event(array(
		'event_type_id' => 'create_task',
		'project_id' => $project->getID(),
		'user_1_id' => Session::getUserID(),
		'item_1_id' => $task->getID()
	));
	$logEvent->save();
	
	// we're done here
	Session::setMessage('You created a new task.');
	$json = array('success' => '1', 'successUrl' => Url::task($task->getID()));
	echo json_encode($json);
} elseif($action == 'edit') {
	// flag default is false; assume nothing is modified to start
	$modified = false;
	
	// is title modified?
	if($title != $task->getTitle()) {
		// save changes
		$oldTitle = $task->getTitle();
		$task->setTitle($title);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_title',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $task->getID(),
			'data_1' => $oldTitle,
			'data_2' => $title
		));
		$logEvent->save();
		// set flag
		$modified = true;
	}
	
	// is leader modified?
	if($leaderID != $task->getLeaderID()) {
		// save changes
		$oldLeaderID = $task->getLeaderID();
		$task->setLeaderID($leaderID);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_leader',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'user_2_id' => $leaderID,
			'item_1_id' => $task->getID(),
			'data_1' => $oldLeaderID,
			'data_2' => $leaderID
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}
	
	// is description modified?
	if($description != $task->getDescription()) {
		// save changes
		$oldDescription = $task->getDescription();
		$task->setDescription($description);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_description',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $task->getID(),
			'data_1' => $oldDescription,
			'data_2' => $description
		));
		$logEvent->save();
		// set flag
		$modified = true;	
	}
	
	// is status modified?
	if($status != $task->getStatus()) {
		// save changes
		$oldStatus = $task->getStatus();
		$task->setStatus($status);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_status',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $task->getID(),
			'data_1' => $oldStatus,
			'data_2' => $status
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}
	
	// is num needed modified?
	if($numNeeded != $task->getNumNeeded()) {
		// save changes
		$oldNumNeeded = $task->getNumNeeded();
		$task->setNumNeeded($numNeeded);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_num_needed',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $task->getID(),
			'data_1' => $oldNumNeeded,
			'data_2' => $numNeeded
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}
	
	// is deadline modified?
	$formattedDeadline = ($formattedDeadline != '') ? date("Y-m-d H:i:s", $formattedDeadline) : null;
	$oldDeadline = $task->getDeadline();
	if($formattedDeadline != $oldDeadline) {
		// save changes
		$task->setDeadline($formattedDeadline);
		$task->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_task_deadline',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $task->getID(),
			'data_1' => $oldDeadline,
			'data_2' => $formattedDeadline
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}
	
	// // attach any uploads
	// $attached = Upload::attachToItem(
		// $token,
		// Upload::TYPE_TASK,
		// $task->getID(),
		// $project->getID()
	// );	
	
	// if($attached !== false) {
		// // log it
		// $firstUpload = reset($attached);
		// $logEvent = new Event(array(
			// 'event_type_id' => 'edit_task_uploads',
			// 'project_id' => $project->getID(),
			// 'user_1_id' => Session::getUserID(),
			// 'item_1_id' => $task->getID(),
			// 'item_2_id' => $firstUpload->getID()
		// ));
		// $logEvent->save();
		// // set flag
		// $modified = true;
	// }
	
	// check flag
	if($modified) {
		Session::setMessage('You edited this task.');
		$json = array('success' => '1', 'successUrl' => Url::task($task->getID()));
		echo json_encode($json);		
	} else {
		$json = array('error' => 'No changes were detected.');
		exit(json_encode($json));	
	}
} elseif($action == 'accept') {
	// accept the task
	$accepted = new Accepted(array(
		'creator_id' => Session::getUserID(),
		'project_id' => $project->getID(),
		'task_id' => $taskID,
		'status' => Accepted::STATUS_ACCEPTED
	));
	$accepted->save();
	
	// log it
	$logEvent = new Event(array(
		'event_type_id' => 'accept_task',
		'project_id' => $project->getID(),
		'user_1_id' => Session::getUserID(),
		'item_1_id' => $accepted->getID(),
		'item_2_id' => $taskID
	));
	$logEvent->save();
	
	// send us back
	Session::setMessage('You accepted the task. Good luck!');
	$json = array('success' => '1', 'successUrl' => Url::task($taskID));
	echo json_encode($json);
} elseif($action == 'comment') {
	$message = Filter::formattedText($_POST['message']);
	if($message == '') {
		$json = array('error' => 'Your comment cannot be empty.');
		exit(json_encode($json));		
	} else {
		// post the comment
		$comment = new Comment(array(
			'creator_id' => Session::getUserID(),
			'project_id' => $project->getID(),
			'task_id' => $taskID,
			'message' => $message
		));
		$comment->save();
		// re-save now that we have an ID
		$comment->setParentID($comment->getID());
		$comment->save();
		
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'create_task_comment',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $comment->getID(),
			'item_2_id' => $taskID,
			'data_1' => $message
		));
		$logEvent->save();
		
		// send us back
		Session::setMessage('You commented on this task.');
		$json = array('success' => '1');
		echo json_encode($json);
	}
} elseif($action == 'comment-reply') {
	$commentID = Filter::numeric($_POST['commentID']);
	$message = Filter::formattedText($_POST['message']);
	if($message == '') {
		$json = array('error' => 'Your reply cannot be empty.');
		exit(json_encode($json));		
	} else {
		// post the comment
		$reply = new Comment(array(
			'creator_id' => Session::getUserID(),
			'project_id' => $project->getID(),
			'task_id' => $taskID,
			'parent_id' => $commentID,
			'message' => $message
		));
		$reply->save();
		
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'create_task_comment_reply',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'item_1_id' => $commentID,
			'item_2_id' => $reply->getID(),
			'item_3_id' => $taskID,
			'data_1' => $message
		));
		$logEvent->save();
		
		// send us back
		Session::setMessage('You replied to a comment on this task.');
		$json = array('success' => '1');
		echo json_encode($json);
	}	
} else {
	$json = array('error' => 'Invalid action.');
	exit(json_encode($json));
}