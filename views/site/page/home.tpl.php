<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$theme = Theme::load(DEFAULT_THEME_ID);
// set up stylesheet variables
$jqueryuiStylesheet = $theme->getJqueryuiStylesheet();
$pipelineStylesheet = $theme->getPipelineStylesheet();

?>
<!DOCTYPE html>
<html class="home">
<head>
	<title><?= PIPELINE_NAME ?> - powered by Pipeline</title>
	<link rel="icon" type="image/png" href="<?= Url::images() ?>/icons/clapperboard.png" />
	<script type="text/javascript" src="<?= Url::scripts() ?>/modernizr.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/basic.css" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/<?= $pipelineStylesheet ?>" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/<?= $jqueryuiStylesheet ?>" />
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript"> 
		google.load("jquery", "1");
		google.load("jqueryui", "1.8.16");
		google.setOnLoadCallback(function(){});
	</script>
</head>
<body>

	<div class="top">

		<div class="funnel">
	
		<h1><?= PIPELINE_NAME ?><span>powered by <a id="pipeline-logo-lg" href="http://pipeline.cc.gatech.edu/" title="Pipeline">&nbsp;</a></span></h1>
		
		</div>

	</div><!-- .top -->

	<div class="middle">

		<div class="funnel">
	
			<h2>Pipeline is a new way to create together on the Web. <a href="http://pipeline.cc.gatech.edu/features">Learn more</a></h2>
	
			<div class="line"></div>
			
			<div class="get-started">
				<p style="font-size: 120%;">First time here?</p>
				<a href="<?= Url::consent() ?>">Get Started</a>
				
				<p>Already registered?</p>
				<a href="<?= Url::logIn() ?>">Log In</a>
			</div>		
			
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
	
			
			<?php
				// $SOUP->render('site/partial/activity', array(
					// 'size' => 'large',
					// 'showProject' => true,
					// 'title' => 'Recent Activity in '.PIPELINE_NAME
				// ));
			?>
	
		
		</div>
	
	</div><!-- .middle -->

	<div class="bottom">
		<div class="funnel">
			<a href="http://pipeline.cc.gatech.edu/">About Pipeline</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/features">Features</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/screenshots">Screenshots</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/blog">Blog</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/code">Source Code</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/research">Research</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/team">Team</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/contact">Contact</a>
		</div>
	</div>


</body>
</html>