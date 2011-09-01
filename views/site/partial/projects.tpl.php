<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects', array());
$user = $SOUP->get('user', null);
$title = $SOUP->get('title', 'Projects');

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->startBlockSet('body');

if($projects != null) {
?>
<ul class="segmented-list projects">
<?php foreach($projects as $p): ?>
	<li>
		<?php
		
		$status = '';
		if(ProjectUser::isCreator($user->getID(), $p->getID())) {
			$status = 'creator';
		} elseif(ProjectUser::isOrganizer($user->getID(), $p->getID())) {
			$status = 'organizer';
		} elseif(ProjectUser::isContributor($user->getID(), $p->getID())) {
			$status = 'contributor';
		} elseif(ProjectUser::isFollower($user->getID(), $p->getID())) {
			$status = 'follower';
		}
		
		?>
		<h6 class="primary"><a href="<?= Url::project($p->getID()) ?>"><?= $p->getTitle() ?></a>&nbsp;<span class="status"><?= formatProjectStatus($p->getStatus()) ?></span></h6>
		<p class="secondary"><?= $status ?></p>
	</li>
<?php endforeach; ?>
</ul>
<?php
} else {
	echo '<p>(none)</p>';
}

$fork->endBlockSet();
$fork->render('site/partial/panel');

?>