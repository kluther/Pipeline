<?php
require_once('./../../global.php');
include_once TEMPLATE_PATH.'/site/helper/format.php';

// get submitted data

$title =    Filter::text($_POST['txtTitle']);
$pitch =    Filter::formattedText($_POST['txtPitch']); 
$specs =    Filter::text($_POST['txtSpecs']);
$rules =    Filter::text($_POST['txtRules']);
$deadline = Filter::text($_POST['txtDeadline']);

// validate data
if(empty($title)) {
	$json = array( 'error' => 'You must provide a project title.' );
	exit(json_encode($json));
}

if(empty($pitch)) {
	$json = array( 'error' => 'You must provide a project pitch.' );
	exit(json_encode($json));
}

// must be valid deadline or empty
$formattedDeadline = strtotime($deadline);
if( ($formattedDeadline === false) && ($deadline != '') ) {
	$json = array('error' => 'Deadline must be a valid date or empty.');
	exit(json_encode($json));
}

// format deadline for MYSQL
$formattedDeadline = ($formattedDeadline != '') ? date("Y-m-d H:i:s", $formattedDeadline) : null;

// create the project

$project = new Project(array(
	'creator_id' => Session::getUserID(),
	'title' => $title,
	'slug' => '',
	'pitch' => $pitch,
	'specs' => $specs,
	'rules' => $rules,
	'status' => Project::STATUS_PRE_PRODUCTION,
	'deadline' => $formattedDeadline,
	'private' => 0
));
$project->save();

// generate slug from project title/ID
$slug = toAscii($title);
$slug = $project->getID().'-'.$slug;

// save new slug
$project->setSlug($slug);
$project->save();

// add creator as ProjectUser
$pu = new ProjectUser(array(
	'project_id' => $project->getID(),
	'user_id' => Session::getUserID(),
	'relationship' => ProjectUser::CREATOR
));
$pu->save();

// log it

$logEvent = new Event(array(
	'event_type_id' => 'create_project',
	'project_id' => $project->getID(),
	'user_1_id' => Session::getUserID()
));
$logEvent->save();

// send us back

//$successURL = Url::project($project->getID());
$successURL = Url::peopleInvite($project->getID());

Session::setMessage('Project created! Now you need some members.');
$json = array(
	'success' => '1',
	'successUrl' => $successURL
	);
echo json_encode($json);	