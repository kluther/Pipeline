<?php
require_once("../../global.php");

$email = Filter::email($_POST['email']);
$name = Filter::text($_POST['name']);

// must provide valid email
if(empty($email)) {
	$json = array( 'error' => 'You must provide a valid email address.' );
	exit(json_encode($json));
}

// save consent
$consent = new Consent(array(
	'email' => $email,
	'name' => $name
));
$consent->save();

// email confirmation
$body = '<p>You have consented to participate in a Georgia Tech research study looking at how people collaborate online.</p>';
if(!empty($name)) {
	$body .= "<p>Additionally, you have requested that we use your real name if we refer to you in our publications.</p>";
}
$body .= '<p>The consent form is available for viewing and printing at <a href="http://www.scribd.com/doc/66688220/Adult-Web-Consent-Testing?secret_password=4nzp5x09db318hcu9e2">this link</a>. Please retain a copy for your records.</p>';
$body .= '<p>If you have any questions or concerns, please contact the research team at <a href="mailto:pipeline@cc.gatech.edu">pipeline@cc.gatech.edu</a>. Thank you for your participation!</p>';
$body .= '<p>-- <a href="'.BASE_URI.'">The Pipeline team</a></p>';
$newEmail = array(
	'to' => $email,
	'subject' => 'Georgia Tech study consent form',
	'message' => $body
);
Email::send($newEmail);

// send us back
Session::setMessage("Consent form complete! Please register an account.");
$json = array('success' => '1', 'successUrl' => Url::register($email));
echo json_encode($json);	
