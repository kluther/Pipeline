<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects');
$users = $SOUP->get('users');
$events = $SOUP->get('events');

$fork = $SOUP->fork();
$fork->set('pageTitle', "Admin");
$fork->set('headingURL', Url::admin());
$fork->startBlockSet('body');

?>

<td class="left">

<?php
	$data = array();
	foreach($events as $e) {
		$data[] = array(
			$e->getEventTypeID(),
			formatProjectLink($e->getProjectID()),
			formatUserLink($e->getUser1ID()),
			formatTimeTag($e->getDateCreated())
		);
	}
	$SOUP->render('site/partial/itemTable', array(
		'title' => 'Last 50 Events ('.count($events).')',
		'ths' => array('Event Type', 'Project', 'User1', 'Date/time'),
		'data' => $data
		
	));
?>


<?php
	$SOUP->render('site/partial/massEmail',array(
	));
?>

</td>

<td class="right">


<?php
	$data = array();
	foreach($projects as $p) {
		$data[] = array(
			formatProjectLink($p->getID()),
			formatUserLink($p->getCreatorID()),
			formatTimeTag($p->getDateCreated()),
			'<a href="'.Url::activity($p->getID()).'">'.count(Event::getByProjectID($p->getID())).'</a>'
		);
	}
	$SOUP->render('site/partial/itemTable', array(
		'title' => 'All Projects ('.count($projects).')',
		'ths' => array('Title', 'Creator', 'Created', '#&nbsp;Events'),
		'data' => $data
		
	));
?>

<?php
	$data = array();
	foreach($users as $u) {
		$data[] = array(
			formatUserLink($u->getID()),
			formatTimeTag($u->getDateCreated()),
			count(Event::getUserEvents($u->getID()))
		);
	}
	$SOUP->render('site/partial/itemTable', array(
		'title' => 'All Users ('.count($users).')',
		'ths' => array('Username', 'Registered', '#&nbsp;Events'),
		'data' => $data
		
	));
?>

</td>

<?php

$fork->endBlockSet();
$fork->render('site/partial/page');