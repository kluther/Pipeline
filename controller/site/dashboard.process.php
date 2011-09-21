<?php
require_once("../../global.php");

$inviteID = Filter::numeric($_POST['inviteID']);
$invite = Invitation::load($inviteID);
$relationship = $invite->getRelationship();

$response = Filter::alphanum($_POST['response']);
if($response == 'accept') {
	$response = Invitation::ACCEPTED;
} else {
	$response = Invitation::DECLINED;
}

if($response == Invitation::ACCEPTED) {
	if($relationship == ProjectUser::CONTRIBUTOR) {
		// join the user to the task
		$a = new Accepted(array(
			'creator_id' => $invite->getInviteeID(),
			'project_id' => $invite->getProjectID(),
			'task_id' => $invite->getTaskID(),
			'status' => Accepted::STATUS_ACCEPTED
		));
		$a->save();
	} else {
		// add the user to the project
		$pu = new ProjectUser(array(
			'project_id' => $invite->getProjectID(),
			'user_id' => $invite->getInviteeID(),
			'relationship' => $invite->getRelationship()
		));
		$pu->save();
	}
	
	// get event type ID and success message
	if($relationship == ProjectUser::ORGANIZER) {
		$eventTypeID = 'accept_organizer_invitation';
		$successMsg = 'You accepted the organizer invitation.';
	} elseif($relationship == ProjectUser::FOLLOWER) {
		$eventTypeID = 'accept_follower_invitation';
		$successMsg = 'You accepted the follower invitation.';
	} else {
		$eventTypeID = 'accept_contributor_invitation';
		$successMsg = 'You accepted the contributor invitation.';
	}	
} else {
	// get event type ID and success message
	if($relationship == ProjectUser::ORGANIZER) {
		$eventTypeID = 'decline_organizer_invitation';
		$successMsg = 'You declined the organizer invitation.';
	} elseif($relationship == ProjectUser::FOLLOWER) {
		$eventTypeID = 'decline_follower_invitation';
		$successMsg = 'You declined the follower invitation.';
	} else {
		$eventTypeID = 'decline_contributor_invitation';
		$successMsg = 'You declined the contributor invitation.';
	}	
}

// update the invite
$invite->setResponse($response);
$invite->setDateResponded(date("Y-m-d H:i:s"));
$invite->save();

// log the event
$logEvent = new Event(array(
	'event_type_id' => $eventTypeID,
	'user_1_id' => $invite->getInviteeID(),
	'user_2_id' => $invite->getInviterID(),
	'project_id' => $invite->getProjectID(),
	'item_1_id' => $invite->getID(),
	'item_2_id' => $invite->getTaskID()
));
$logEvent->save();

// set confirm message and send us away
Session::setMessage($successMsg);
$json = array( 'success' => '1');
echo json_encode($json);

?>