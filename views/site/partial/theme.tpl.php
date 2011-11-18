<?php

$userThemeID = $SOUP->get('userThemeID');
$themes = $SOUP->get('themes');

$fork = $SOUP->fork();
$fork->set('title', 'Theme');
$fork->startBlockSet('body');

?>

<script type="text/javascript">

	$(document).ready(function(){
		$('#selTheme').val('<?= $userThemeID ?>');
		$('#selTheme').change(function(){
			buildPost({
				'processPage': '<?= Url::settingsProcess() ?>',
				'info': {
					'action': 'theme',
					'themeID': $('#selTheme').val()
				}
			});
		});
	});

</script>

<p>Choose a theme from the list.</p>

<div class="line"> </div>

<div class="clear">
	<label for="selTheme">Theme</label>
	<div class="input">
		<select id="selTheme" name="selTheme">
<?php
	if(!empty($themes)) {
		foreach($themes as $t) {
			echo '<option value="'.$t->getID().'">'.$t->getName().'</option>';
		}
	}
?>
		</select>
	</div>
</div>

<?php

$fork->endBlockSet();
$fork->render('site/partial/panel');