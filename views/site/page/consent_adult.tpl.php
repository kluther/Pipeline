<?php

	$fork = $SOUP->fork();
	$fork->set('pageTitle', 'Study Consent');
	$fork->startBlockSet('body');
?>

<td class="left">

	<?php
		$SOUP->render('site/partial/consent_adult',array(
			'title' => "Adult Consent Form"
			)
		);
	?>

</td>

<td class="right"> </td>


<?php
	$fork->endBlockSet();
 	$fork->render('site/partial/page');