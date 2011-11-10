<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$messages = $SOUP->get('messages');
$title = $SOUP->get('title', 'Messages');
$id = $SOUP->get('id', 'messages');

$fork = $SOUP->fork();
$fork->set('title', $title);
$fork->set('id', $id);
$fork->set('creatable', true);
$fork->set('createLabel', 'Compose Message');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#<?= $id ?> .createButton').click(function(){
		window.location = '<?= Url::messageNew() ?>';
	});
});

</script>

<?php if(empty($messages)): ?>

<p>(none)</p>

<?php else: ?>

<table class="items messages">
	<tr>
		<th style="padding-left: 22px;">Subject</th>
		<th>Sender</th>
		<th>Sent</th>
	</tr>
<?php

foreach($messages as $m) {
	$read = ($m->getDateRead() != null) ? 'read' : 'unread';
	echo '<tr>';
	echo '<td class="subject '.$read.'">';
	// show "Re:" if this is a reply to a previous message
	$subject = $m->getSubject();
	if($m->getParentID() != $m->getID()) {
		$subject = 'Re: '.$subject;
	}
	echo '<h6><a href="'.Url::message($m->getID()).'">'.$subject.'</a></h6>';
	echo '<p>';
	$body = strip_tags(formatInboxMessage($m->getBody()));
	echo substr($body,0,35);
	if(strlen($body) > 35)
		echo '&hellip;';	
	echo '</p>';
	echo '</td>';
	echo '<td class="sender">'.formatUserLink($m->getSenderID()).'</td>';
	echo '<td class="sent">'.formatTimeTag($m->getDateSent()).'</td>';
	echo '</tr>';
}

?>

</table>

<?php endif; ?>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');