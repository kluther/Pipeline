<?php

require_once('./../../global.php');

$action = Filter::text($_POST['action']);

if($action == 'reset') {
	// reset password
	
	// find the user, if it exists
	$username = Filter::text($_POST['username']);
	if(Filter::email($username))
		$user = User::loadByEmail($username);
	else
		$user = User::loadByUsername($username);
	
	if(empty($user)) {
		$json = array('error' => 'That username or email address was not found.');
		exit(json_encode($json));	
	}
	
	// generate a new password
	$newPass = uniqid();
	$encryptedNewPass = sha1($newPass);
	$user->setPassword($encryptedNewPass);
	$user->save();
	
	// email confirmation
	$body = '<p>The password for the account <a href="'.Url::user($user->getID()).'">'.$user->getUsername().'</a> has been changed.</p>';
	$body .= '<p>The new password is: '.$newPass.'</p>';
	$body .= '<p>Once you log in, you can change this password to something more memorable by clicking the "Edit" button on your <a href="'.Url::user($user->getID()).'">profile</a> page.</p>';
	$body .= '<p>Note: If you did not request this password change, please contact the '.PIPELINE_NAME.' staff.</p>';
	$newEmail = array(
		'to' => $user->getEmail(),
		'subject' => '['.PIPELINE_NAME.'] Password changed for '.$user->getUsername(),
		'message' => $body
	);
	Email::send($newEmail);
	
	// redirect
	Session::setMessage('Your password was reset. Please check your email for the new password.');
	$json = array(
		'success' => '1',
		'successUrl' => Url::logIn()
	);
	exit(json_encode($json));	
	
} elseif ($action == 'login') {

	// assign POST vars to local vars after escaping and removing unwanted spacing.
	if (!empty($_POST['username']) && !empty($_POST['password']))
	{
		$username = Filter::text($_POST['username']);
		$password = sha1(Filter::text($_POST['password']));
		$referer = Filter::text($_POST['referer']);

		// figure out if user provided username or email address
		if(Filter::email($username))
			$user = User::loadByEmail($username);
		else
			$user = User::loadByUsername($username);

		if($user != null)
		{
			if ($password == $user->getPassword())
			{
				Session::signIn($user->getID());
				if(!empty($referer) && ($referer != Url::forgotPassword())) {
					$json = array(
						'success' => '1',
						'successUrl' => $referer
					);
				} else {
					$json = array('success' => 1);
				}
				exit(json_encode($json));
			}
			else
			{
				$json = array('error' => 'Invalid username or password. Please try again.');
				exit(json_encode($json));
			}
		}
		else
		{
			$json = array('error' => 'Invalid username or password. Please try again.');
			exit(json_encode($json));
		}
	}
	else
	{
		if (empty($_POST['username']) && empty($_POST['password']))
		{
			$json = array('error' => 'Please enter your username and password to log in.');
			exit(json_encode($json));
		}
		else if (empty($_POST['username']) && !empty($_POST['password']))
		{
			$json = array('error' => 'Your username or email address is required to log in.');
			exit(json_encode($json));
		}
		else if (!empty($_POST['username']) && empty($_POST['password']))
		{
			$json = array('error' => 'Please enter your password to log in.');
			exit(json_encode($json));
		}
	}

} else {
	$json = array('error' => 'Invalid action.');
	exit(json_encode($json));
}