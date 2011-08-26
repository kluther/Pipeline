<?php
require_once('./../../global.php');

// check project
$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);
if($project == null) {
	Session::setMessage('That project does not exist.');
	header('Location: '.Url::error());
	exit();
}

$action = Filter::alphanum($_POST['action']);

if($action == "pitch") {
	// edit the pitch
	$newPitch = Filter::formattedText($_POST['pitch']);
	$oldPitch = $project->getPitch();
	
	if($oldPitch != $newPitch)
	{
		$project->setPitch($newPitch);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_pitch',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'data_1' => $oldPitch,
			'data_2' => $newPitch
		));
		$logEvent->save();
		
		$json = array( 'success' => '1' );
		Session::setMessage("You edited the pitch.");
		echo json_encode($json);
	} else {
		$json = array( 'error' => 'You did not make any changes.' );
		exit(json_encode($json));
	}
} elseif($action == "specs") {
	// edit the specs
	$newSpecs = Filter::text($_POST['specs']);
	$oldSpecs = $project->getSpecs();
	
	if($oldSpecs != $newSpecs)
	{
		$project->setSpecs($newSpecs);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_specs',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'data_1' => $oldSpecs,
			'data_2' => $newSpecs
		));
		$logEvent->save();
		
		$json = array( 'success' => '1' );
		Session::setMessage("You edited the specs.");
		echo json_encode($json);
	} else {
		$json = array( 'error' => 'You did not make any changes.' );
		exit(json_encode($json));
	}
} elseif($action == "rules"){
	// edit the rules
	$newRules = Filter::text($_POST['rules']);
	$oldRules = $project->getRules();
	
	if($oldRules != $newRules)
	{
		$project->setRules($newRules);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_rules',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'data_1' => $oldRules,
			'data_2' => $newRules
		));
		$logEvent->save();
		
		$json = array( 'success' => '1' );
		Session::setMessage("You edited the rules.");
		echo json_encode($json);
	} else {
		$json = array( 'error' => 'You did not make any changes.' );
		exit(json_encode($json));
	}
} elseif($action == "progress") {
	// check for valid date
	$deadline = Filter::text($_POST['deadline']);
	$formattedDeadline = strtotime($deadline);
	if( ($formattedDeadline === false) && ($deadline != '') ) {
		$json = array('error' => 'Deadline must be a valid date or empty.');
		exit(json_encode($json));
	}

	// edit progress
	$modified = false;
	
	// is status modified?
	$newStatus = Filter::numeric($_POST['status']);
	if($newStatus != $project->getStatus()) {
		// save changes
		$oldStatus = $project->getStatus();
		$project->setStatus($newStatus);
		$project->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_project_status',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'data_1' => $oldStatus,
			'data_2' => $newStatus
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}
	
	// is deadline modified?
	$formattedDeadline = ($formattedDeadline != '') ? date("Y-m-d H:i:s", $formattedDeadline) : null;
	$oldDeadline = $project->getDeadline();
	if($formattedDeadline != $oldDeadline) {
		// save changes
		$project->setDeadline($formattedDeadline);
		$project->save();
		// log it
		$logEvent = new Event(array(
			'event_type_id' => 'edit_project_deadline',
			'project_id' => $project->getID(),
			'user_1_id' => Session::getUserID(),
			'data_1' => $oldDeadline,
			'data_2' => $formattedDeadline
		));
		$logEvent->save();
		// set flag
		$modified = true;		
	}

	// check flag
	if($modified) {
		Session::setMessage('You edited the progress.');
		$json = array('success' => '1');
		echo json_encode($json);		
	} else {
		$json = array('error' => 'No changes were detected.');
		exit(json_encode($json));	
	}	
} else {
	$json = array( 'error' => 'Invalid action.' );
	exit(json_encode($json));
}