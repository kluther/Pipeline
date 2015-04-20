<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$replies = $SOUP->get('replies',array());
$discussion = $SOUP->get('discussion', null);
$page = $SOUP->get('page');
$numPages = $SOUP->get('numPages');
$isReflection = $SOUP->get('isReflection', false);
//$token = Upload::generateToken();

$thisURL = Url::discussion($discussion->getID());

// any logged-in user can discuss
$hasReplyPermission = ( Session::isLoggedIn() &&
					!$discussion->getLocked() &&
					($page == $numPages) );
$hasLockPermission = (Session::isAdmin() ||
					$project->isTrusted(Session::getUserID()) ||
					$project->isCreator(Session::getUserID()) );

// css for discussion title
$cssLocked = ($discussion->getLocked()) ? ' class="locked"' : '';

// choose label depending on whether Reflection or Discussion
$itemLabel = ($isReflection) ? 'Reflection' : 'Discussion';
	
$fork = $SOUP->fork();
$fork->set('id', 'discussion');
$fork->set('title', $itemLabel);
if($hasLockPermission) {
	$fork->set('editable', true);
	if($discussion->getLocked()) {
		$fork->set('editLabel', 'Unlock '.$itemLabel);
	} else {
		$fork->set('editLabel', 'Lock '.$itemLabel);
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

		$('#selDiscussionPages').change(function(){
			var url = '<?= $thisURL ?>';
			var pageNum = $(this).val();
			window.location = url+'/'+pageNum;
		});
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

// pagination, if necessary
if($numPages > 1) {
	$fork->startBlockSet('footer');

	if($page != 1) {
		$olderURL = $thisURL;
		$olderURL .= '/'.($page-1);
		echo '<a href="'.$olderURL.'">&laquo; Older</a> ';
	}

	echo '<select id="selDiscussionPages">';
	for($i=1; $i<=$numPages; $i++) {
		$selected = ($i == $page) ? ' selected="selected"' : '';
		echo '<option value="'.$i.'"'.$selected.'>page '.$i.'</option>';
	}
	echo '</select>';

	if($page != $numPages) {
		$newerURL = $thisURL;
		$newerURL .= '/'.($page+1);
		echo ' <a href="'.$newerURL.'">Newer &raquo;</a>';
	}

	$fork->endBlockSet();
}

$fork->render('site/partial/panel');