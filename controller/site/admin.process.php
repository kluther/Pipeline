<?php
require_once("../../global.php");
require_once TEMPLATE_PATH.'/site/helper/format.php';

$subject = Filter::text($_POST['subject']);
$body = Filter::formattedText($_POST['body']);

if(empty($subject) || empty($body)) {
	$json = array( 'error' => 'You must provide a subject and body for the email.' );
	exit(json_encode($json));
}

$massEmailAddresses = User::getMassEmailAddresses();

$newEmail = array(
	'to' => SMTP_FROM_EMAIL,
	'subject' => '['.PIPELINE_NAME.'] '.$subject,
	'message' => $body,
	'bcc' => $massEmailAddresses
);
$sendEmail = Email::send($newEmail);
if(!$sendEmail !== true) {
	$json = array( 'error' => $sendEmail);
	exit(json_encode($json));
}

$numMassEmails = formatCount(count($massEmailAddresses),'user','users');

// send us back
Session::setMessage("Your mass email was sent to ".$numMassEmails.".");
$json = array('success' => '1');
echo json_encode($json);	