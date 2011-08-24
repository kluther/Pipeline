<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

function formatEvent($event)
{
		switch($event->getEventTypeID())
		{
			case 'create_project':
				$formatted = sprintf("%s created the project %s.",
						formatUserLink($event->getUser1ID()),
						formatProjectLink($event->getProjectID())
					);
				break;
			case 'edit_pitch':
				$formatted = sprintf("%s edited the %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::pitch($event->getProjectID()).'">pitch</a>'
					);
				break;
			case 'edit_specs':
				$formatted = sprintf("%s edited the %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::specs($event->getProjectID()).'">specs</a>'
					);
				break;
			case 'edit_rules':
				$formatted = sprintf("%s edited the %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::rules($event->getProjectID()).'">rules</a>'
					);					
				break;	
			case 'create_discussion':
				$discussion = Discussion::load($event->getItem1ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s created the discussion %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);					
				break;				
			case 'create_discussion_reply':
				$discussion = Discussion::load($event->getItem1ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s replied to %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);					
				break;
			case 'create_task':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s created the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);
				break;
			case 'edit_task_title':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the name of the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);
				break;
			case 'edit_task_status':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$status = $event->getData2();
				if($status == Task::STATUS_CLOSED) {
					$formatted = sprintf("%s closed the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>'
						);
				} else {
					$formatted = sprintf("%s opened the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>'
						);				
				}
				break;			
			case 'edit_task_num_needed':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the # people needed for the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);
				break;			
			case 'edit_task_leader':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s changed the leader of the task %s to %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						formatUserLink($event->getUser2ID())
					);
				break;				
			case 'edit_task_description':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the instructions for the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);
				break;
			case 'edit_task_uploads':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the uploads for the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);
				break;				
			case 'edit_task_deadline':
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$deadline = $event->getData2();
				if($deadline != '') {
					$formatted = sprintf("%s changed the deadline for the task %s to %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>',
							strftime("%a, %b %d, %Y", strtotime($deadline))
						);
				} else {
					$formatted = sprintf("%s removed the deadline for the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>'
						);				
				}
				break;
			case 'accept_task':
				$accepted = Accepted::load($event->getItem1ID());
				//$acceptedUrl = Url::updates($accepted->getID());
				$task = Task::load($event->getItem2ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$formatted = sprintf("%s joined the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
					);			
				break;
			case 'edit_accepted_status':
				$update = $event->getItem1ID();
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());							
				$accepted = Accepted::load($event->getItem2ID());
				$task = Task::load($event->getItem3ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$status = $event->getData2();
				if($status == Accepted::STATUS_RELEASED) {
					$formatted = sprintf("%s has %s working on the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">stopped</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
						);	
				} elseif($status == Accepted::STATUS_ACCEPTED) {
					$formatted = sprintf("%s has %s working on the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">started</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
						);				
				} elseif($status == Accepted::STATUS_FEEDBACK) {
					$formatted = sprintf("%s is %s on his/her work on the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">seeking feedback</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
						);					
				} elseif($status == Accepted::STATUS_COMPLETED) {
					$formatted = sprintf("%s has %s working on the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">finished</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
						);						
				} elseif($status == Accepted::STATUS_PROGRESS) {
					$formatted = sprintf("%s is %s on the task %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">working</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>'
						);						
				}				
				break;
			case 'create_task_comment':
				$task = Task::load($event->getItem2ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s commented on the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);		
				break;
			case 'create_task_comment_reply':
				$task = Task::load($event->getItem3ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s replied to a comment on the task %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);		
				break;	
			case 'create_update_comment':
				$update = Update::load($event->getItem2ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s commented on the update %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);		
				break;
			case 'create_update_comment_reply':
				$update = Update::load($event->getItem3ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s replied to a comment on the update %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>'
					);		
				break;
			case 'create_update':
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s created the update %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>'
					);					
				break;				
			case 'edit_update':
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the update %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>'
					);					
				break;
			default:
				$formatted = 'Event type "'.$event->getEventTypeID().'" not found.';
		}
		return $formatted;
}
