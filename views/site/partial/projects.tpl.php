<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects', array());
$user = $SOUP->get('user', null);
$title = $SOUP->get('title', 'Projects');
$id = $SOUP->get('id', 'projects');

$hasPermission = Session::isLoggedIn();

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('id', $id);
if($hasPermission) {
	$fork->set('creatable', true);
	$fork->set('createLabel', 'New Project');
}

$fork->startBlockSet('body');

if($hasPermission) {
?>

<script type="text/javascript">

$('#<?= $id ?> .createButton').click(function(){
	window.location = '<?= Url::projectNew() ?>';
});

</script>

<?php
}

if(!empty($projects)) {
?>

<table class="projects">
	<tr>
		<th style="padding-left: 22px;">Project</th>
		<th>Status</th>
		<th>Deadline</th>
		<th>Members</th>
		<?php if(!is_null($user)): ?>
		<th>Role</th>
		<?php endif; ?>
	</tr>
<?php
	foreach($projects as $p) {
		echo '<tr>';
		// title and pitch
		echo '<td class="name">';
		echo '<h6><a href="'.Url::project($p->getID()).'">'.$p->getTitle().'</a></h6>';
		// echo '<p>';
		// echo formatPitch(substr($p->getPitch(),0,70));
		// if(strlen($p->getPitch()) > 70)
			// echo '...';
		// echo '</p>';
		echo '</td>';
		// status
		$status = formatProjectStatus($p->getStatus());
		echo '<td class="status">'.$status.'</td>';
		// deadline
		$deadline = $p->getDeadline();
		$deadline = (empty($deadline)) ? '--' : formatTimeTag($deadline);
		echo '<td class="deadline">'.$deadline.'</td>';
		// members
		$members = count($p->getAllMembers())+1;
		echo '<td class="members"><a href="'.Url::people($p->getID()).'">'.$members.'</a></td>';
		// role
		if(!is_null($user)) {
			$relationship = '';
			if(ProjectUser::isCreator($user->getID(), $p->getID())) {
				$relationship = 'creator';
			} elseif(ProjectUser::isTrusted($user->getID(), $p->getID())) {
				$relationship = 'trusted member';			
			} elseif(ProjectUser::isMember($user->getID(), $p->getID())) {
				$relationship = 'member';
			} elseif(ProjectUser::isFollower($user->getID(), $p->getID())) {
				$relationship = 'follower';
			}
			echo '<td class="role">'.$relationship.'</td>';
		}
		echo '</tr>';
	}
 ?>
</table>
<?php
} else {
	echo '<p>(none)</p>';
}

$fork->endBlockSet();
$fork->render('site/partial/panel');

?>