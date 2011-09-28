<?php
require_once("../../global.php");

$slug = Filter::text($_GET['slug']);
$project = Project::getProjectFromSlug($slug);

// kick us out if slug invalid
if($project == null) {
	header('Location: '.Url::error());
	exit();
}

// $creatorID = $project->getCreatorID();
// $creator = User::load($creatorID);

// $organizers = $project->getOrganizers();
// $organizerInvites = Invitation::getByProjectID($project->getID(), ProjectUser::ORGANIZER);

// $contributors = $project->getOnlyContributors();
// $contributorInvites = Invitation::getByProjectID($project->getID(), ProjectUser::CONTRIBUTOR);

$followers = $project->getFollowers();
//$followerInvites = $project->getInvitations();

$banned = $project->getBanned();

$allMembers = $project->getAllMembers();
$memberInvites = $project->getInvitations();

//$trustedContributors = $project->getTrustedContributors();
//$untrustedContributors = $project->getUntrustedContributors();

//$trustedFollowers = $project->getTrustedFollowers();
//$untrustedFollowers = $project->getUntrustedFollowers();

$discussions = Discussion::getPeopleDiscussionsByProjectID($project->getID(), 3);

$events = Event::getPeopleEventsByProjectID($project->getID(), 5);

$soup = new Soup();
$soup->set('project', $project);
$soup->set('allMembers', $allMembers);
$soup->set('memberInvites', $memberInvites);
// $soup->set('creator', $creator);
// $soup->set('organizers', $organizers);
// $soup->set('organizerInvites', $organizerInvites);
// $soup->set('contributors', $contributors);
//$soup->set('trustedContributors', $trustedContributors);
//$soup->set('untrustedContributors', $untrustedContributors);

//$soup->set('trustedFollowers', $trustedFollowers);
//$soup->set('untrustedFollowers', $untrustedFollowers);

// $soup->set('contributorInvites', $contributorInvites);
$soup->set('banned', $banned);
$soup->set('followers', $followers);
//$soup->set('followerInvites', $followerInvites);
$soup->set('events', $events);

$soup->set('discussions', $discussions);

$soup->render('project/page/credits');