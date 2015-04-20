<?php
require_once '../../global.php';
require_once TEMPLATE_PATH.'/site/helper/format.php';

$body = $SOUP->get('body');
$pageTitle = $SOUP->get('pageTitle');
$headingURL = $SOUP->get('headingURL', '#');
$selected = $SOUP->get('selected', null);
$breadcrumbs = $SOUP->get('breadcrumbs', null);
$project = $SOUP->get('project', null);

$showChatBox = false;


if(!empty($project)) {
	$status = formatProjectStatus($project->getStatus());
        $projectId = $project->getID();
        $projectSlug = $project->getSlug();
        $projectTitle = $project->getTitle();
}

if(Session::isLoggedIn()) {
	$user = Session::getUser();
	// update last login
	$user->setLastLogin(date("Y-m-d H:i:s"));
	$user->save();
	// load unread messages
	$numUnread = $user->getNumUnreadMessages();
	// load custom theme, if specified
	if($user->getThemeID() != null) {
		$theme = Theme::load($user->getThemeID());
	} else {
		$theme = Theme::load(DEFAULT_THEME_ID); // load default theme
	}
        //Only allow chatting for logged in members within the context of a project
        //Chat is only supported at the chat room level for now
        if (!empty($projectId)){
            //Check whether chat is enabled in config.php and user is part of the project or an admin user
            If ((ENABLE_CHAT==1) && ($project->isMember(Session::getUserID()) || $project->isTrusted(Session::getUserID()) || Session::isAdmin() || $project->isCreator(Session::getUserID()))) {
                $showChatBox = true;
            }
        }
} else {
	$theme = Theme::load(DEFAULT_THEME_ID); // load default theme
}

// set up stylesheet variables
$jqueryuiStylesheet = $theme->getJqueryuiStylesheet();
$pipelineStylesheet = $theme->getPipelineStylesheet();

?>
<!DOCTYPE html>
<html>
<head>
	<title><?= PIPELINE_NAME ?> - <?= $pageTitle ?></title>
	<link rel="icon" type="image/png" href="<?= Url::images() ?>/icons/clapperboard.png" />
	<script type="text/javascript" src="<?= Url::scripts() ?>/modernizr.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/basic.css" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/<?= $pipelineStylesheet ?>" />
	<link rel="stylesheet" type="text/css" href="<?= Url::styles() ?>/<?= $jqueryuiStylesheet ?>" />
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <link rel="stylesheet" type="text/css"  media="all" href="<?= Url::styles() ?>/chat.css" />
        <script type="text/javascript"> 
		google.load("jquery", "1");
		google.load("jqueryui", "1.8.16");
		google.setOnLoadCallback(function(){});
	</script>
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
				<li class="right"><a href="<?= Url::inbox() ?>">Inbox<?= ($numUnread>0) ? '<span class="unread">'.$numUnread.'</span>' : '' ?></a></li>		
				<li class="right"><a href="<?= Url::profile() ?>"><?= Session::getUsername() ?></a></li>				
			<?php else: ?>
				<li class="right"><a href="<?= Url::consent() ?>">Register</a></li>
				<li class="right"><a href="<?= Url::logIn() ?>">Log In</a></li>
			<?php endif; ?>
				<li class="left"><a href="<?= Url::projectNew() ?>">Start a Project</a></li>			
				<li class="left"><a href="<?= Url::findProjects() ?>">Find Projects</a></li>				
				<li class="left"><a href="<?= Url::help() ?>">Help</a></li>
			<?php if(Session::isAdmin()): ?>
				<li class="left"><a href="<?= Url::admin() ?>">Admin</a></li>
			<?php endif; ?>			
			</ul>
		</div><!-- end .funnel -->	
	</div><!-- end .primary-nav -->
	<div class="funnel">
		<div class="heading">
			<?php
				if($project != null) {
					$SOUP->render('project/partial/yourRole', array(
					));
				}
			?>			
			<h2>
				<a href="<?= $headingURL ?>"><?= $pageTitle ?></a>
				<?php if($project != null): ?><span class="status"><?= $status ?></span><?php endif; ?>
			</h2>
		</div><!-- end .funnel -->
	</div><!-- end .heading -->
<?php if($selected != null): ?>
	<div class="funnel">
		<div class="secondary-nav">
			<ul>
			<?php if($project != null): ?>
				<li><a <?= ($selected == "details")?'class="selected"':'' ?> href="<?= Url::details($project->getID()) ?>">Basics</a></li>
				<li><a <?= ($selected == "tasks")?'class="selected"':'' ?> href="<?= Url::tasks($project->getID()) ?>">Tasks</a></li>
				<li><a <?= ($selected == "people")?'class="selected"':'' ?> href="<?= Url::people($project->getID()) ?>">People</a></li>
				<li><a <?= ($selected == "discussions")?'class="selected"':'' ?> href="<?= Url::discussions($project->getID()) ?>">Discussions</a></li>
				<li><a <?= ($selected == "files")?'class="selected"':'' ?> href="<?= Url::files($project->getID()) ?>">Files</a></li>
				<li><a <?= ($selected == "activity")?'class="selected"':'' ?> href="<?= Url::activity($project->getID()) ?>">Activity</a></li>
                <li><a <?= ($selected == "reflections")?'class="selected"':'' ?> href="<?= Url::reflections($project->getID()) ?>">Reflections</a></li>
			<?php endif; ?>
			</ul>
		</div><!-- end .secondary-nav -->
	</div><!-- end .funnel -->
<?php endif; ?>
</div><!-- end .page-header -->
<div class="page-body">
	<div class="funnel">
		<?php if(count($breadcrumbs) > 1): ?>
			<div class="breadcrumbs">
			<!--h3><?= $breadcrumbs[0][0] ?></h3-->
			<?php foreach (array_slice($breadcrumbs,0,-1) as $crumb): ?>
				<a href="<?= $crumb[1] ?>">
					<?= $crumb[0] ?>
				</a>
				&raquo;
			<?php endforeach; ?>
			<?= $breadcrumbs[count($breadcrumbs)-1][0] ?>
			</div>
		<?php endif; ?>
		<table id="columns">
			<tr><?= $body ?></tr>
		</table>
                <?php if (!empty($chatBox)) echo $chatBox; ?>
	</div><!-- end .funnel -->
</div><!-- end .page-body -->
<div class="page-footer">
	<div class="funnel">
		powered by <a id="pipeline-logo-sm" href="http://pipeline.cc.gatech.edu/" title="Pipeline">&nbsp;</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/features">Features</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/screenshots">Screenshots</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/blog">Blog</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/code">Source Code</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/research">Research</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/team">Team</a> <span class="slash">/</span> <a href="http://pipeline.cc.gatech.edu/contact">Contact</a>
	</div>
</div>
<div id="feedback"></div><!-- #feedback -->

<script type="text/javascript" src="<?= Url::scripts() ?>/common.js"></script>
<script type="text/javascript" src="<?= Url::scripts() ?>/feedback.js"></script>
<script type="text/javascript">
    var chatLocation = <?php echo json_encode(Url::base()."/chat.php"); ?>;
    var slug = <?php echo json_encode($_GET['slug']) ?>;
    var lastRecord = 0;
    var pageId = <?php echo time() ?>;
</script>
<?php If ($showChatBox): ?>
    <script type="text/javascript" src="<?= Url::scripts() ?>/chat.js"></script>
<?php endif; ?>
    
<script type="text/javascript">
	$(document).ready(function(){
                <?php If ($showChatBox): ?>                        
                    chatWith('<?= str_replace(" ","_",$projectTitle) ?>');
                <?php endif; ?>
                
                <?php if(Session::getMessage() != null): ?>
                    displayNotification("<?= Session::getMessage() ?>");
                <?php Session::clearMessage(); ?>
                <?php endif; ?>
                
        });
</script>



</body>
</html>