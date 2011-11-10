<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$message = $SOUP->get('message');
$replies = $message->getReplies();

$fork = $SOUP->fork();
$fork->set('id', 'message');
$fork->set('title', 'Message');
$fork->startBlockSet('body');
?>

<script type="text/javascript">
	$(document).ready(function(){	
		$('#btnReply').click(function(){
			buildPost({
				'processPage':'<?= Url::inboxProcess() ?>',
				'info':{
					'action':'reply',
					'messageID':<?= $message->getID() ?>,
					'body':$('#txtReplyMessage').val()
				},
				'buttonID':'#btnReply'
			});
		});
	});
</script>

<ul class="segmented-list replies">
	<li><h5><?= $message->getSubject() ?></h5></li>
<?php

foreach($replies as $reply) {
	echo '<li>';
	echo formatUserPicture($reply->getSenderID(), 'small');
	echo '<p class="headline">'.formatUserLink($reply->getSenderID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($reply->getDateSent()).'</span></p>';
	echo '<p class="message">'.formatInboxMessage($reply->getBody()).'</p>';
	echo '</li>';
}
?>
	<li class="reply">
		<textarea id="txtReplyMessage"></textarea>	
		<div class="buttons">
			<input type="button" class="right" id="btnReply" value="Send Reply" />
			<p class="right"><a class="help-link" href="<?= Url::help() ?>#help-html-allowed">Some HTML allowed</a></p>
		</div>
	</li>
</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');