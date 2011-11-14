<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$replies = $SOUP->get('replies',array());
$discussion = $SOUP->get('discussion', null);
//$token = Upload::generateToken();

// include discussion starter in list of replies
if($replies != null)
	array_unshift($replies, $discussion);
else
	$replies = array($discussion);
	
// any logged-in user can discuss
$hasReplyPermission = ( Session::isLoggedIn() &&
					!$discussion->getLocked() );
$hasLockPermission = (Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

// css for discussion title
$cssLocked = ($discussion->getLocked()) ? ' class="locked"' : '';
	
$fork = $SOUP->fork();
$fork->set('id', 'discussion');
$fork->set('title', 'Discussion');
if($hasLockPermission) {
	$fork->set('editable', true);
	if($discussion->getLocked()) {
		$fork->set('editLabel', 'Unlock Discussion');
	} else {
		$fork->set('editLabel', 'Lock Discussion');
	}
}
$fork->startBlockSet('body');
?>

<script type="text/javascript">
	$(document).ready(function(){
		
		<?php if($hasLockPermission): ?>
		$('#discussion .editButton').click(function(){
			buildPost({
				'processPage':'<?= Url::discussionProcess($discussion->getID()) ?>',
				'info':{
					'action':'lock',
					'discussionID':<?= $discussion->getID() ?>
				},
				'buttonID':$(this)
			});
		});
		<?php endif; ?>
		
		<?php if($hasReplyPermission): ?>		
		$('#btnReply').click(function(){
			buildPost({
				'processPage':'<?= Url::discussionProcess($discussion->getID()) ?>',
				'info':{
					'action':'reply',
					'discussionID':<?= $discussion->getID() ?>,
					'message':$('#txtReplyMessage').val()
				},
				'buttonID':'#btnReply'
			});
		});
		<?php endif; ?>
	});
</script>

<ul class="segmented-list replies">
	<li><h5<?= $cssLocked ?>><?= $discussion->getTitle() ?></h5></li>
<?php

foreach($replies as $reply) {
	echo '<li>';
	echo formatUserPicture($reply->getCreatorID(), 'small');
	echo '<p class="headline">'.formatUserLink($reply->getCreatorID(), $project->getID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($reply->getDateCreated()).'</span></p>';
	echo '<p class="message">'.formatDiscussionReply($reply->getMessage()).'</p>';
	echo '</li>';
}
?>
	<?php if($hasReplyPermission): ?>
	<li class="reply">
		<textarea id="txtReplyMessage"></textarea>	
		<div class="buttons">
			<input type="button" class="right" id="btnReply" value="Post Reply" />
			<p class="right"><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
		</div>
	</li>
	<?php elseif($discussion->getLocked()): ?>
	<li class="reply"><span class="locked">This discussion is locked.</span></li>
	<?php endif; ?>	
</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');