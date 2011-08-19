<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

$replies = $SOUP->get('replies',array());
$discussion = $SOUP->get('discussion', null);
$token = Upload::generateToken();

// include discussion starter in list of replies
if($replies != null)
	array_unshift($replies, $discussion);
else
	$replies = array($discussion);
	
$fork = $SOUP->fork();
$fork->set('title', 'Discussion');
$fork->startBlockSet('body');
?>
<script type="text/javascript">
	$(document).ready(function(){
		//$('#txtReplyMessage').focus();
		$('#btnReply').mousedown(function(){
			buildPost({
				'processPage':'<?= Url::discussionProcess($discussion->getID()) ?>',
				'info':{
					'action':'reply',
					'token':'<?= $token ?>',
					'discussionID':<?= $discussion->getID() ?>,
					'message':$('#txtReplyMessage').val()
				},
				'buttonID':'#btnReply'
			});
		});
	});
</script>

<ul class="segmented-list replies">
	<li><h5><?= $discussion->getTitle() ?></h5></li>
<?php

foreach($replies as $reply)
{
	$uploads = Upload::getByItemID(Upload::TYPE_DISCUSSION, $reply->getID(), false);
	
	echo '<li>';
	echo '<a class="picture large" href="'.Url::user($reply->getCreatorID()).'"><img src="'.Url::userPictureLarge($reply->getCreatorID()).'" /></a>';
	echo '<p class="headline">'.formatUserLink($reply->getCreatorID()).' <span class="slash">/</span> <span class="when">'.formatTimeTag($reply->getDateCreated()).'</span></p>';
	echo '<p class="message">'.html_entity_decode($reply->getMessage()).'</p>';
	echo '</li>';
}
?>
	<li class="reply">
		<textarea id="txtReplyMessage"></textarea>	
		<div class="buttons">
			<input type="button" class="right" id="btnReply" value="Reply" />
			<p class="right">Allowed tags: &lt;a&gt; &lt;strong&gt; &lt;b&gt; &lt;em&gt; &lt;i&gt;</p>
		</div>
	</li>
</ul>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');