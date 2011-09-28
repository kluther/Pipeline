<?php
	$fork = $SOUP->fork();
	$fork->set('pageTitle', 'Study Consent');
	$fork->startBlockSet('body');
?>
<script type="text/javascript">


</script>

<td class="td">

	<?php
		$SOUP->render('site/partial/consent_minor',array(
			'title' => "Minor Consent Form"
			)
		);
	?>
</td>

<td class="right"> </td>
<td class="extra"> </td>

<?php
	$fork->endBlockSet();
 	$fork->render('site/partial/page');