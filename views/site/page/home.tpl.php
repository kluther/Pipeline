<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= PIPELINE_NAME ?> | powered by Pipeline</title>
	<link rel="icon" type="image/png" href="<?= Url::images() ?>/icons/clapperboard.png" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/basic.css" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/<?= STYLE_SHEET ?>" />
	<?php if(STYLE_SHEET == 'dark.css'): ?>
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/jquery-ui-darkness.css" />
	<?php else: ?>
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/jquery-ui-redmond.css" />
	<?php endif; ?>
	<link href='http://fonts.googleapis.com/css?family=Coustard' rel='stylesheet' type='text/css' />
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript"> 
		google.load("jquery", "1");
		google.load("jqueryui", "1.8.16");
		google.setOnLoadCallback(function(){});
	</script>
</head>
<body>

<div class="home">

	<div class="top">

		<h1><?= PIPELINE_NAME ?><span>powered by <a href="http://pipeline.cc.gatech.edu/">Pipeline</a></span></h1>

	</div><!-- .top -->

	<table><tr>
	
	<td class="left">

		<h2>Pipeline is a new way to create together on the Web.</h2>

		<div class="line"></div>
		
		<div class="features">

			<h3>Lead projects your way</h3>
			<p>Run a democracy, a dictatorship, or something in between, and adjust on-the-fly.</p>

			<h3>Volunteer-friendly</h3>
			<p>Find ways to contribute that match your interests, abilities, and available time.</p>

			<h3>Get creative, fast</h3>
			<p>Share and review video, audio, images, and animation, all from within your browser.</p>

		</div><!-- .features -->

		<div class="line"></div>
		
		<div class="meta">
			<p>Completely free and open-source</p>
			<p>Web-based software works in any modern browser</p>
			<p>Developed by researchers at Georgia Tech</p>
		</div><!-- .meta -->

	</td><!-- .left -->
	
	<td class="get-started">
		<p style="font-size: 120%;">First time here?</p>
		<a href="<?= Url::consent() ?>">Get Started</a>
		
		<p>Already registered?</p>
		<a href="<?= Url::logIn() ?>">Log In</a>
	</td>		

	<td class="right">
		<?php
			$SOUP->render('site/partial/activity', array(
				'size' => 'large',
				'showProject' => true,
				'title' => 'Recent Activity in '.PIPELINE_NAME
			));
		?>
	</td><!-- .right -->

	</tr></table>

	<div class="bottom">
		<a href="http://pipeline.cc.gatech.edu/">About Pipeline</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/blog">Blog</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/research">Research</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/team">Team</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/contact">Contact</a>
	</div>

</div><!-- .home -->

</body>
</html>