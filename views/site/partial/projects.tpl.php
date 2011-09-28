<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects', array());
$user = $SOUP->get('user', Session::getUser());
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
		
		$deadline = ($p->getDeadline() != '') ? 'due '.formatTimeTag($p->getDeadline()) : 'no deadline';
		
		?>
		<h6 class="primary"><a href="<?= Url::project($p->getID()) ?>"><?= $p->getTitle() ?></a>&nbsp;<span class="status"><?= $relationship ?></span></h6>
		<p class="secondary"><span class="status"><?= formatProjectStatus($p->getStatus()) ?></span> <span class="slash">/</span> <?= $deadline ?></p>
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