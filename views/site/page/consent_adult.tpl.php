<?php
	$fork = $SOUP->fork();
	$fork->set('pageTitle', 'Study Consent');
	$fork->startBlockSet('body');
?>

<div class="left">

	<?php
		$SOUP->render('site/partial/consent_adult',array(
			'title' => "Adult Consent Form"
			)
		);
	?>

</div>

<div class="right"> </div>

<?php
	$fork->endBlockSet();
 	$fork->render('site/partial/page');