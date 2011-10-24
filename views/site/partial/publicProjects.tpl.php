<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$projects = $SOUP->get('projects', array());

$fork = $SOUP->fork();
$fork->set('title', 'Public Projects');
$fork->startBlockSet('body');

if($projects != null) {
?>
<ul class="segmented-list projects">
<?php foreach($projects as $p): ?>
	<li>
		<h6 class="primary"><a href="<?= Url::project($p->getID()) ?>"><?= $p->getTitle() ?></a>&nbsp;<span class="status"><?= formatProjectStatus($p->getStatus()) ?></span></h6>
		<p class="secondary">
			<a href="<?= Url::people($p->getID()) ?>"><?= formatCount(count($p->getAllMembers())+1, 'member', 'members') ?></a>
			<span class="slash">/</span>
			<a href="<?= Url::tasks($p->getID()) ?>"><?= formatCount(count($p->getTasks(Task::STATUS_OPEN)), 'open task', 'open tasks') ?></a>
			<span class="slash">/</span>
			<?= ($p->getDeadline() != null) ? 'due '.formatTimeTag($p->getDeadline()) : 'no deadline' ?>
		</p>
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