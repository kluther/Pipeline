<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$creator = $SOUP->get('creator', null);
$organizers = $SOUP->get('organizers', array());
$contributors = $SOUP->get('contributors', array());
//$followers = $SOUP->get('followers', array());
$users = $SOUP->get('users', array());
$style = $SOUP->get('style', 'full');

$fork = $SOUP->fork();

if($style == 'full') {
	$fork->set('editable', true);
	$fork->set('editLabel', 'Edit Organizers');
	$fork->set('creatable', true);
	$fork->set('createLabel', 'Invite People');	
	}

$fork->startBlockSet('body');

?>

<?php if($style == "list"): ?>
<p>
	<?php if($users != ''): ?>
		<?php foreach($users as $user): ?>
			<?php if(!ProjectUser::isContributor($user->getID(), $project->getID())): ?>
			<a href="<?= Url::user($user->getID()) ?>"><?= $user->getUsername() ?></a> 
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</p>
<?php elseif($style == "matrix"): ?>

<?php else: ?>
<ul class="segmented-list users">
<?php
echo formatUserRow($creator->getID(), 'creator');
foreach($organizers as $o) {
	echo formatUserRow($o->getID(), 'organizer');
}
foreach($contributors as $c) {
	echo formatUserRow($c->getID(), 'contributor');
}
?>
</ul>

<?php endif; ?>

<?

$fork->endBlockSet();
$fork->render('site/partial/panel');