<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

function formatEvent($event, $showProject=false)
{
		switch($event->getEventTypeID())
		{
			case 'create_user':
				$formatted = sprintf("%s registered for %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::base().'">'.PIPELINE_NAME.'</a>'
					);
					break;
			case 'accept_organizer_invitation':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : "this project";
				$formatted = sprintf("%s accepted %s's invitation to help organize %s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);
				break;
			case 'accept_follower_invitation':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : "this project";
				$formatted = sprintf("%s accepted %s's invitation to follow %s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);
				break;
			case 'make_organizer':
				$predicate = ($showProject) ? ' of the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s made %s an organizer%s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);
				break;	
			case 'revoke_organizer':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s revoked %s's organizer status%s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);					
				break;				
			case 'ban_user':
				$predicate = ($showProject) ? ' from the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s banned %s%s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);
				break;
			case 'unban_user':
				$predicate = ($showProject) ? ' from the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s unbanned %s%s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID()),
						$predicate
					);
				break;
			case 'create_project':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
				$formatted = sprintf("%s created %s.",
						formatUserLink($event->getUser1ID()),
						formatProjectLink($event->getProjectID())
					);
				break;
			case 'edit_pitch':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::pitch($event->getProjectID()).'">pitch</a>',
						$predicate
					);
				break;
			case 'edit_specs':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::specs($event->getProjectID()).'">specs</a>',
						$predicate
					);
				break;
			case 'edit_rules':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::rules($event->getProjectID()).'">rules</a>',
						$predicate
					);					
				break;	
			case 'edit_project_status':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
				$status = $event->getData2();
				$formatted = sprintf("%s changed the %s of %s to &ldquo;%s.&rdquo;",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::status($event->getProjectID()).'">status</a>',
						$predicate,
						formatProjectStatus($status)
					);					
				break;
			case 'edit_project_deadline':
				$deadline = $event->getData2();
				if($deadline != null) {
					$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
					$formatted = sprintf("%s changed the %s of %s to %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.Url::deadline($event->getProjectID()).'">deadline</a>',
							$predicate,
							strftime("%a, %b %d, %Y", strtotime($deadline))
						);
				} else {
					$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
					$formatted = sprintf("%s removed the %s for %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.Url::deadline($event->getProjectID()).'">deadline</a>',
							$predicate
						);				
				}
				break;				
			case 'create_discussion':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$discussion = Discussion::load($event->getItem1ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s posted the discussion %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);					
				break;				
			case 'create_discussion_reply':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$discussion = Discussion::load($event->getItem2ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s replied to the discussion %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);					
				break;
			case 'create_task':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s created the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;
			case 'edit_task_title':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the name of the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;
			case 'edit_task_status':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$status = $event->getData2();
				if($status == Task::STATUS_CLOSED) {
					$formatted = sprintf("%s closed the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate
						);
				} else {
					$formatted = sprintf("%s opened the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate
						);				
				}
				break;			
			case 'edit_task_num_needed':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the # people needed for the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;			
			case 'edit_task_leader':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s changed the leader of the task %s%s to %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate,
						formatUserLink($event->getUser2ID())
					);
				break;				
			case 'edit_task_description':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the instructions for the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;
			case 'edit_task_uploads':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the uploads for the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;				
			case 'edit_task_deadline':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$deadline = $event->getData2();
				if($deadline != '') {
					$formatted = sprintf("%s changed the deadline for the task %s%s to %s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate,
							strftime("%a, %b %d, %Y", strtotime($deadline))
						);
				} else {
					$formatted = sprintf("%s removed the deadline for the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate
						);				
				}
				break;
			case 'accept_task':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$accepted = Accepted::load($event->getItem1ID());
				//$acceptedUrl = Url::updates($accepted->getID());
				$task = Task::load($event->getItem2ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$formatted = sprintf("%s joined the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
						$predicate
					);			
				break;
			case 'edit_accepted_status':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = $event->getItem1ID();
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());							
				$accepted = Accepted::load($event->getItem2ID());
				$task = Task::load($event->getItem3ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$status = $event->getData2();
				if($status == Accepted::STATUS_RELEASED) {
					$formatted = sprintf("%s has %s working on the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">stopped</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);	
				} elseif($status == Accepted::STATUS_ACCEPTED) {
					$formatted = sprintf("%s has %s working on the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">started</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);				
				} elseif($status == Accepted::STATUS_FEEDBACK) {
					$formatted = sprintf("%s is %s on his/her work on the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">seeking feedback</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);					
				} elseif($status == Accepted::STATUS_COMPLETED) {
					$formatted = sprintf("%s has %s working on the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">finished</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);						
				} elseif($status == Accepted::STATUS_PROGRESS) {
					$formatted = sprintf("%s is %s on the task %s%s.",
							formatUserLink($event->getUser1ID()),
							'<a href="'.$updateUrl.'">working</a>',
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);						
				}				
				break;
			case 'create_task_comment':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem2ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s commented on the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;
			case 'create_task_comment_reply':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem3ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s replied to a comment on the task %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;	
			case 'create_update_comment':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem2ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s commented on the update %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;
			case 'create_update_comment_reply':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem3ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s replied to a comment on the update %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;
			case 'create_update':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s created the update %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;				
			case 'edit_update_title':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the title of the update %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;
			case 'edit_update_message':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the contents of the update %s%s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;				
			default:
				$formatted = 'Event type "'.$event->getEventTypeID().'" not found.';
		}
		return $formatted;
}
