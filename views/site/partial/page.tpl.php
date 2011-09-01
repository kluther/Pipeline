<?php
require_once TEMPLATE_PATH.'/site/helper/format.php';

$body = $SOUP->get('body');
$pageTitle = $SOUP->get('pageTitle');
$headingURL = $SOUP->get('headingURL', '#');
$selected = $SOUP->get('selected', null);
$breadcrumbs = $SOUP->get('breadcrumbs', null);
$project = $SOUP->get('project', null);

if($project != null)
	$status = formatProjectStatus($project->getStatus());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= PIPELINE_NAME ?> - <?= $pageTitle ?></title>
	<link rel="icon" type="image/png" href="<?= Url::images() ?>/icons/clapperboard.png" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/basic.css" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/light.css" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/jquery-ui-1.8.16.custom.css" />
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript"> 
		google.load("jquery", "1");
		google.load("jqueryui", "1.8.16");
		google.setOnLoadCallback(function(){});
	</script>
	<script type="text/javascript" src="<?= Url::scripts() ?>/common.js"></script>
	<script type="text/javascript" src="<?= Url::scripts() ?>/feedback.js"></script>
	<?php if(Session::getMessage() != null): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			displayNotification("<?= Session::getMessage() ?>");
		});
	</script>		
	<?php Session::clearMessage(); ?>
	<?php endif; ?>
</head>
<body>

<div class="page-header">
	<div class="primary-nav">
		<div class="funnel">
			<h1><a href="<?= Url::base() ?>"><?= PIPELINE_NAME ?></a></h1>
			<ul>
			<?php if(Session::isLoggedIn()): ?>
				<li class="right"><a href="<?= Url::logOut() ?>">Log Out</a></li>
				<li class="right"><a href="<?= Url::settings() ?>">Settings</a></li>
				<li class="right"><a href="<?= Url::profile() ?>"><?= Session::getUsername() ?></a></li>	
				<!--li class="right"><a href="<?= Url::inbox() ?>">Inbox</a></li-->				
			<?php else: ?>
				<li class="right"><a href="<?= Url::register() ?>">Register</a></li>
				<li class="right"><a href="<?= Url::logIn() ?>">Log In</a></li>
			<?php endif; ?>
				<li class="left"><a href="<?= Url::findProjects() ?>">Find Projects</a></li>
				<!--li class="left"><a href="<?= Url::startAProject() ?>">Start a Project</a></li-->
				<li class="left"><a href="http://pipeline.cc.gatech.edu/">About Pipeline</a></li>
			</ul>
		</div><!-- end .funnel -->
	</div><!-- end .primary-nav -->
	<div class="heading">
		<div class="funnel">
			<h2>
				<a href="<?= $headingURL ?>"><?= $pageTitle ?></a>
				<?php if($project != null): ?><span class="status"><?= $status ?></span><?php endif; ?>
			</h2>
		</div><!-- end .funnel -->
	</div><!-- end .heading -->
<?php if($selected != null): ?>
	<div class="secondary-nav">
		<div class="funnel">
			<ul>
			<?php if($project != null): ?>
				<li><a <?= ($selected == "details")?'class="selected"':'' ?> href="<?= Url::details($project->getID()) ?>">Basics</a></li>
				<li><a <?= ($selected == "tasks")?'class="selected"':'' ?> href="<?= Url::tasks($project->getID()) ?>">Tasks</a></li>
				<li><a <?= ($selected == "people")?'class="selected"':'' ?> href="<?= Url::people($project->getID()) ?>">People</a></li>
				<li><a <?= ($selected == "discussions")?'class="selected"':'' ?> href="<?= Url::discussions($project->getID()) ?>">Discussions</a></li>
				<li><a <?= ($selected == "activity")?'class="selected"':'' ?> href="<?= Url::activity($project->getID()) ?>">Activity</a></li>
			<?php endif; ?>
			</ul>
		</div><!-- end .funnel -->
	</div><!-- end .secondary-nav -->
<?php endif; ?>
</div><!-- end .page-header -->
<div class="page-body">
	<div class="funnel">
		<?php if(count($breadcrumbs) > 1): ?>
			<div class="breadcrumbs">
			<h3><?= html_entity_decode($breadcrumbs[0][0]) ?></h3>
			<?php foreach (array_slice($breadcrumbs, 1) as $crumb): ?>

					&laquo;
					<a href="<?= $crumb[1] ?>">
						<?= html_entity_decode($crumb[0]) ?>
					</a>
	
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?= $body ?>
	</div><!-- end .funnel -->
</div><!-- end .page-body -->
<div id="feedback"></div><!-- #feedback -->

</body>
</html>