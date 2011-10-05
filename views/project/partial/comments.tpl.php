<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$project = $SOUP->get('project');
$comments = $SOUP->get('comments',array());
$processURL = $SOUP->get('processURL');
$parentID = $SOUP->get('parentID');

// any logged-in user may comment
$hasPermission = ( Session::isLoggedIn() &&
					!$project->isBanned(Session::getUserID()) );

//$fork = $SOUP->fork();
//$fork->set('title', 'Comments');
//$fork->startBlockSet('body');
?>

<?php if($hasPermission): ?>

<script type="text/javascript">
	$(document).ready(function(){
		//$('#txtComment').focus();
		$('#btnComment').click(function(){
			buildPost({
				'processPage':'<?= $processURL ?>',
				'info':{
					'action':'comment',
					'message':$('#txtComment').val()
				},
				'buttonID':'#btnComment'
			});
		});
		$('.replyButton').click(function(){
			var li = $(this).parent();
			var commentID = $(li).attr('id').slice(8);
			
			if($(li).find('.post-reply').is(':visible')) {
				$(li).find('.post-reply').remove();
			} else {
				$(li).append('<div class="hidden post-reply"><div class="line"></div><div><textarea></textarea><div class="buttons"><input type="button" class="right" value="Post Reply" /><p class="right"><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p></div></div></div>');
				$(li).find('.post-reply input[type="button"]').click(function(){
					buildPost({
						'processPage':'<?= $processURL ?>',
						'info':{
							'action':'comment-reply',
							'commentID':commentID,
							'message':$(this).parent().parent().find('textarea').val()
						},
						'buttonID':$(this)
					});
				});
				$(li).find('.post-reply').fadeIn(function(){
					$(li).find('.post-reply textarea').focus();
				});
			}
		});		
	});
</script>

<?php endif; ?>

<div class="line"> </div>

<h5 class="comments">Comments</h5>

<ul class="segmented-list comments">
<?php
if($comments != null) {
	foreach($comments as $comment) {
		echo '<li id="comment-'.$comment->getID().'">';
		echo formatUserPicture($comment->getCreatorID(), 'small');
		if($hasPermission)
			echo '<input class="replyButton" type="button" value="Reply" />';
		echo '<p class="headline">'.formatUserLink($comment->getCreatorID(), $project->getID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($comment->getDateCreated()).'</span></p>';					
		echo '<p class="message">'.formatComment($comment->getMessage()).'</p>';			
		//echo '<p class="when">'.formatTimeTag($comment->getDateCreated()).'</p>';
		echo '</li>';
		
		$replies = $comment->getReplies();
		if($replies != null) {
			foreach($replies as $reply) {
				echo '<li class="comment-reply">';
				echo formatUserPicture($comment->getCreatorID(), 'small');		
				echo '<p class="headline">'.formatUserLink($reply->getCreatorID(), $project->getID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($reply->getDateCreated()).'</span></p>';					
				echo '<p class="message">'.formatComment($reply->getMessage()).'</p>';			
				//echo '<p class="when">'.formatTimeTag($reply->getDateCreated()).'</p>';				
				echo '</li>';
			}
		}
	}
} else {
	echo '<li>(none)</li>';
}
?>

<?php if($hasPermission): ?>

	<li class="comment">
		<textarea id="txtComment"></textarea>
		<div class="buttons">
			<input type="button" class="right" id="btnComment" value="Post Comment" />	
			<p class="right"><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
		</div>
	</li>
	
<?php endif; ?>
</ul>
<?php

//$fork->endBlockSet();
//$fork->render('site/partial/panel');