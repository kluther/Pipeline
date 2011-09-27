<?php
require_once("../../global.php");

$inviteID = Filter::numeric($_POST['inviteID']);
$invite = Invitation::load($inviteID);

if(ProjectUser::isAffiliated($invite->getInviteeID(), $invite->getProjectID())) {
	// already affiliated; can't follow
	Session::setMessage('You are already affiliated with this project.');
	header('Location: '.Url::error());
	exit();
}

$response = Filter::alphanum($_POST['response']);
if($response == 'accept') {
	$response = Invitation::ACCEPTED;
} else {
	$response = Invitation::DECLINED;
}

if($response == Invitation::ACCEPTED) {
	// add the user to the project
	$pu = new ProjectUser(array(
		'project_id' => $invite->getProjectID(),
		'user_id' => $invite->getInviteeID(),
		'relationship' => ProjectUser::FOLLOWER,
		'trusted' => $invite->getTrusted()
	));
	$pu->save();
	
	$eventTypeID = 'accept_follower_invitation';
	$successMsg = 'You accepted the follower invitation.';	
} else {
	$eventTypeID = 'decline_follower_invitation';
	$successMsg = 'You declined the follower invitation.';
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
	'item_1_id' => $invite->getID()
));
$logEvent->save();

// set confirm message and send us away
Session::setMessage($successMsg);
$json = array( 'success' => '1');
echo json_encode($json);

?>