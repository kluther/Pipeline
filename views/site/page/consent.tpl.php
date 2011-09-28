<?php
	$email = $SOUP->get('email');

	$fork = $SOUP->fork();
	$fork->set('pageTitle', 'Study Consent');
	$fork->startBlockSet('body');
?>
<script type="text/javascript">

$(document).ready(function(){
	$('#btnAdult').click(function(){
		window.location = "<?= Url::adultConsent($email) ?>";
		});
	$('#btnMinor').click(function(){
		window.location = "<?= Url::minorConsent() ?>";
		});
	});

</script>

<td class="left">

	<p>Welcome to <?= PIPELINE_NAME ?>!</p>
	<p>We have created this software as part of a research study at <a href="http://www.cc.gatech.edu/">Georgia Tech</a> looking at how people collaborate online. To use this software, you must agree to be in the study.</p>
	<p>First, <strong>please tell us how old you are</strong>.</p>

	<div class="buttons">
		<input id="btnAdult" class="left" type="button" value="18 Or Older" />
		<input id="btnMinor" class="left" type="button" value="Younger Than 18" />
	</div>

</td>

<td class="right"> </td>
<td class="extra"> </td>

<?php
	$fork->endBlockSet();
 	$fork->render('site/partial/page');