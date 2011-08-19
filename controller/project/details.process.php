<?php
require_once('./../../global.php');

//$_POST['action'] = "pitch";
//$_POST['pitch'] = "xxxx";
//$_POST['projectID'] = "1";

$action = Filter::alphanum($_POST['action']);

if($action == "pitch") {
	// edit the pitch
	$newPitch = Filter::formattedText($_POST['pitch']);
	$projectID = Filter::numeric($_POST['projectID']);
	$project = Project::load($projectID);
	$oldPitch = $project->getPitch();
	
	if($oldPitch != $newPitch)
	{
		$project->setPitch($newPitch);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_pitch',
			'project_id' => $projectID,
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
	$projectID = Filter::numeric($_POST['projectID']);
	$project = Project::load($projectID);
	$oldSpecs = $project->getSpecs();
	
	if($oldSpecs != $newSpecs)
	{
		$project->setSpecs($newSpecs);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_specs',
			'project_id' => $projectID,
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
	$projectID = Filter::numeric($_POST['projectID']);
	$project = Project::load($projectID);
	$oldRules = $project->getRules();
	
	if($oldRules != $newRules)
	{
		$project->setRules($newRules);
		$project->save();
		
		$logEvent = new Event(array(
			'event_type_id' => 'edit_rules',
			'project_id' => $projectID,
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
}