<?php

require_once('./../../global.php');

// assign POST vars to local vars after escaping and removing unwanted spacing.
if (!empty($_POST['username']) && !empty($_POST['password']))
{
	$username = Filter::text($_POST['username']);
	$password = sha1(Filter::text($_POST['password']));

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
			$json = array('success' => 1);
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