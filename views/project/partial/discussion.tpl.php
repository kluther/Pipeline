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
$hasPermission = Session::isLoggedIn();
	
$fork = $SOUP->fork();
$fork->set('id', 'discussion');
$fork->set('title', 'Discussion');
$fork->set('creatable', $hasPermission);
$fork->set('createLabel', 'New Discussion');
$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript">
	$(document).ready(function(){
		//$('#txtReplyMessage').focus();
		$('#discussion .createButton').click(function(){
			window.location = '<?= Url::discussionNew($discussion->getProjectID()); ?>';
		});		
		
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
	});
</script>

<?php endif; ?>

<ul class="segmented-list replies">
	<li><h5><?= $discussion->getTitle() ?></h5></li>
<?php

foreach($replies as $reply) {
	echo '<li>';
	echo formatUserPicture($reply->getCreatorID(), 'small');
	echo '<p class="headline">'.formatUserLink($reply->getCreatorID(), $project->getID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($reply->getDateCreated()).'</span></p>';
	echo '<p class="message">'.formatDiscussionReply($reply->getMessage()).'</p>';
	echo '</li>';
}
?>
<?php if($hasPermission): ?>
	<li class="reply">
		<textarea id="txtReplyMessage"></textarea>	
		<div class="buttons">
			<input type="button" class="right" id="btnReply" value="Post Reply" />
			<p class="right"><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
		</div>
	</li>
<?php endif; ?>	
</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');