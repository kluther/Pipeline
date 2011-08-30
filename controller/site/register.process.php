<?php
require_once("../../global.php");
require_once TEMPLATE_PATH.'/site/helper/format.php'; // for formatCount

$action = Filter::text($_POST['action']);

switch($action)
{
	case "check":
		$username = Filter::text($_POST['username']);
		$un = User::loadByUsername($username);
		if($un == null)
			exit("available");
		else
			exit("unavailable");
		break;

	case "register":

		// assign POST data to variables
		$code = 	 Filter::alphanum($_POST['code']);
		$uname = 	 Filter::text($_POST['uname']);
		$pw = 		 Filter::text($_POST['pw']);
		$pw2 = 		 Filter::text($_POST['pw2']);
		$email = 	 Filter::email($_POST['email']);
		$name = 	 Filter::text($_POST['name']);
		$birthdate = Filter::text($_POST['birthdate']);
		$sex =		 Filter::text($_POST['sex']);
		$location =  Filter::text($_POST['location']);
		$biography = Filter::text($_POST['biography']);

		// make sure username is provided
		if($uname == "")
		{
			$json = array( 'error' => 'You must provide a unique username to register.' );
			exit(json_encode($json));
		}
			
		// make sure username doesn't exist
		$un = User::loadByUsername($uname);
		if($un != null)
		{
			$json = array( 'error' => 'Sorry, that username is already taken. Please try another one.' );
			exit(json_encode($json));
		}
			
		// username blacklist
		$blacklist = array(
			"process",
			"------",
			"administrator",
			"create",
			"new",
			"admin",
			"edit",
			"delete",
			"invite",
			"tasks",
			"people",
			"basics",
			"activity"
		);
		foreach($blacklist as $b)
		{
			if($uname == $b)
			{
				$json = array( 'error' => 'Sorry, that username is not allowed.' );
				exit(json_encode($json));
			}
		}
		
		// restrict username to a-zA-Z0-9- and at least 6 chars, max 20
		$pattern = "%^[a-zA-Z0-9-]{6,20}$%";
		if(!preg_match($pattern, $uname))
		{
			$json = array( 'error' => 'Your username must be at least 6 characters and include only letters, numbers, and hyphens.');
			exit(json_encode($json));
		}
		
		// make sure passwords exist and match
		if($pw == "" || $pw2 == "")
		{
			$json = array( 'error' => 'You must provide and confirm a password to register.' );
			exit(json_encode($json));
		}
		
		if($pw != $pw2)
		{
			$json = array( 'error' => 'Sorry, your passwords do not match.' );
			exit(json_encode($json));
		}
		
		// validate email address
		if($email == "")
		{
			$json = array( 'error' => 'You must provide a valid email address to register.' );
			exit(json_encode($json));
		}
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$json = array( 'error' => 'You must provide a valid email address to register.' );
			exit(json_encode($json));
		}	
		
		// convert birthdate to MySQL format
		$dob = strtotime($birthdate);
		$dob = date("Y-m-d", $dob);		
		
		// convert password to MD5 hash
		$pw = sha1($pw);

		// instantiate a User with this data
		$user = new User();
		
		// required fields
		$user->setUsername($uname);		
		$user->setEmail($email);
		$user->setPassword($pw);	
		
		// optional fields
		if($name != '')
			$user->setName($name);
		if($birthdate != '')
			$user->setDOB($dob);
		if($sex != '')
			$user->setSex($sex);
		if($location != '')
			$user->setLocation($location);	
		if($biography != '')
			$user->setBiography($biography);
			
		$user->save(); // save the user
		$user->setLastLogin($user->getDateCreated());
		$user->save(); // save last login as date created
		
		// log the event
		$logEvent = new Event(
			array(
				'event_type_id' => 'create_user',
				'user_1_id' => $user->getId()
			)
		);
		$logEvent->save();
		
		// email confirmation
		$msg = '<p>You have successfully registered for <a href="'.Url::base().'">'.PIPELINE_NAME.'</a>.</p>';
		$msg .= '<p>Your username is '.formatUserLink($user->getID()).'. Have fun!</p>';
		$email = array(
			'to' => $email,
			'subject' => 'Welcome to '.PIPELINE_NAME.'!',
			'message' => $msg
		);
		Email::send($email);
		
		// log us into the new account
		Session::signIn($user->getId());
		
		// see if we were invited to do anything special
		if($code != null) {
			// get the invitation
			$invite = Invitation::findByCode($code);
			// add the user to the project
			$pu = new ProjectUser(array(
				'project_id' => $invite->getProjectID(),
				'user_id' => $user->getID(),
				'relationship' => $invite->getRelationship()
			));
			$pu->save();
			// update the invite
			$invite->setResponse(Invitation::ACCEPTED);
			$invite->setDateResponded(date("Y-m-d H:i:s"));
			$invite->save();
			// send us to the project we just joined
			$successUrl = Url::project($invite->getProjectID());
		} else {
			$successUrl = Url::base();
		}

		// set confirm message and send us away
		Session::setMessage("Registration successful! Welcome aboard.");
		$json = array( 'success' => '1',
			   'successUrl' => $successUrl);
		echo json_encode($json);
		break;
		
	default:
		$json = array( 'error' => 'An error occurred. Please try again.' );
		exit(json_encode($json));
		break;
}