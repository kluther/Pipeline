<?php$project = $SOUP->get('project');$task = $SOUP->get('task');$events = $SOUP->get('events');$update = $SOUP->get('update');//$updateCreator = User::load($update->getCreatorID());$fork = $SOUP->fork();$fork->set('project', $project);$fork->set('pageTitle', $project->getTitle());$fork->set('headingURL', Url::project($project->getID()));$fork->set('selected', "tasks");$fork->set('breadcrumbs', Breadcrumbs::update($update->getID()));$fork->startBlockSet('body');?><td class="left"><?php	$SOUP->render('project/partial/update', array(		'size' => 'large'	));?></td><td class="right"><?php	$SOUP->render('project/partial/userUpdates', array(		'id' => 'updates',		//'size' => 'small',		'title' => 'More Contributions',	//	'user' => $updateCreator	//	'taskUpdates' => false	));?><?php	$SOUP->render('project/partial/tasks', array(		'title' => 'Task Info',		'tasks' => array($task),		'hasPermission' => false,		'size' => 'small'	));?><?php	$SOUP->render('site/partial/activity', array(		'title' => "Recent Activity",		'events' => $events,		'size' => 'small',		'olderURL' => Url::activityTasks($project->getID()),		'class' => 'subtle'		));?></td><?$fork->endBlockSet();$fork->render('site/partial/page');