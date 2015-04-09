<?php
include_once TEMPLATE_PATH.'/site/helper/format.php';

function formatEvent($event, $showProject=false)
{
		switch($event->getEventTypeID())
		{
                        case 'invite_member_email':
                                $predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : "this project";
                                $formatted = sprintf("%s invited %s to join %s.",
                                                formatUserLink($event->getUser1ID(), $event->getProjectID()),
                                                '<a href="mailto:'.$event->getData1().'">'.$event->getData1().'</a>',
                                                $predicate
                                        );
                                        break;
                        case 'invite_member_user':
                                $predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : "this project";
                                $formatted = sprintf("%s invited %s to join %s.",
                                                formatUserLink($event->getUser1ID(), $event->getProjectID()),
                                                formatUserLink($event->getUser2ID(), $event->getProjectID()),
                                                $predicate
                                        );
                                        break;
			case 'create_user':
				$formatted = sprintf("%s registered for %s.",
						formatUserLink($event->getUser1ID()),
						'<a href="'.Url::base().'">'.PIPELINE_NAME.'</a>'
					);
					break;
			case 'send_message':
				$formatted = sprintf("%s sent a message to %s.",
						formatUserLink($event->getUser1ID()),
						formatUserLink($event->getUser2ID())
					);
					break;
			case 'accept_member_invitation':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : "this project";
				$formatted = sprintf("%s accepted %s's invitation to join %s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatUserLink($event->getUser2ID(), $event->getProjectID()),
						$predicate
					);
				break;
			case 'trust_member':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s trusted %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatUserLink($event->getUser2ID(), $event->getProjectID()),
						$predicate
					);
				break;	
			case 'untrust_member':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s untrusted %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatUserLink($event->getUser2ID(), $event->getProjectID()),
						$predicate
					);					
				break;	
			case 'join_project':
				$predicate = ($showProject) ? ' '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s joined the project%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						$predicate
					);					
				break;					
			case 'leave_project':
				$predicate = ($showProject) ? ' '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s left the project%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						$predicate
					);					
				break;	
			case 'follow_project':
				$predicate = ($showProject) ? ' '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s followed the project%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						$predicate
					);					
				break;	
			case 'unfollow_project':
				$predicate = ($showProject) ? ' '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s unfollowed the project%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						$predicate
					);					
				break;				
			case 'ban_user':
				$predicate = ($showProject) ? ' from the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s banned %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatUserLink($event->getUser2ID(), $event->getProjectID()),
						$predicate
					);
				break;
			case 'unban_user':
				$predicate = ($showProject) ? ' from the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s unbanned %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatUserLink($event->getUser2ID(), $event->getProjectID()),
						$predicate
					);
				break;
			case 'create_project':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
				$formatted = sprintf("%s created %s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						formatProjectLink($event->getProjectID())
					);
				break;
			case 'edit_pitch':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.Url::pitch($event->getProjectID()).'">pitch</a>',
						$predicate
					);
				break;
			case 'edit_specs':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.Url::specs($event->getProjectID()).'">specs</a>',
						$predicate
					);
				break;
			case 'edit_rules':
				$predicate = ($showProject) ? ' for the project '.formatProjectLink($event->getProjectID()) : '';
				$formatted = sprintf("%s edited the %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.Url::rules($event->getProjectID()).'">rules</a>',
						$predicate
					);					
				break;	
			case 'edit_project_status':
				$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
				$status = $event->getData2();
				$formatted = sprintf("%s changed the %s of %s to &ldquo;%s.&rdquo;",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.Url::deadline($event->getProjectID()).'">deadline</a>',
							$predicate,
							strftime("%a, %b %d, %Y", strtotime($deadline))
						);
				} else {
					$predicate = ($showProject) ? 'the project '.formatProjectLink($event->getProjectID()) : 'this project';
					$formatted = sprintf("%s removed the %s for %s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);					
				break;		
			case 'lock_discussion':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$discussion = Discussion::load($event->getItem1ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s locked the discussion %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);					
				break;				
			case 'unlock_discussion':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$discussion = Discussion::load($event->getItem1ID());
				$title = $discussion->getTitle();
				$url = Url::discussion($discussion->getID());
				$formatted = sprintf("%s unlocked the discussion %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate
						);
				} else {
					$formatted = sprintf("%s opened the task %s%s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
				$numNeeded = $event->getData2();
				if($numNeeded != '') {
					$formatted = sprintf("%s changed the # people needed for the task %s%s to %s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate,
							$numNeeded
						);
				} else {
					$formatted = sprintf("%s changed the # people needed for the task %s%s to &#8734;.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate,
							$numNeeded
						);				
				}
				break;			
			case 'edit_task_leader':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s changed the leader of the task %s%s to %s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate,
						formatUserLink($event->getUser2ID(), $event->getProjectID())
					);
				break;				
			case 'edit_task_description':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the instructions for the task %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);
				break;
			case 'edit_task_uploads':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$task = Task::load($event->getItem1ID());
				$title = $task->getTitle();
				$url = Url::task($task->getID());
				$formatted = sprintf("%s edited the attached files for the task %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$url.'">'.$title.'</a>',
							$predicate,
							strftime("%a, %b %d, %Y", strtotime($deadline))
						);
				} else {
					$formatted = sprintf("%s removed the deadline for the task %s%s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
						$predicate
					);			
				break;
			case 'release_task':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$accepted = Accepted::load($event->getItem1ID());
				//$acceptedUrl = Url::updates($accepted->getID());
				$task = Task::load($event->getItem2ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$formatted = sprintf("%s left the task %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
						$predicate
					);			
				break;				
			case 'edit_accepted_status':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());							
				$accepted = Accepted::load($event->getItem2ID());
				$task = Task::load($event->getItem3ID());
				$taskTitle = $task->getTitle();
				$taskUrl = Url::task($task->getID());
				$status = $event->getData2();
				if($status == Accepted::STATUS_FEEDBACK) {
					$formatted = sprintf("%s is seeking feedback on his/her work on the task %s%s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);					
				} elseif($status == Accepted::STATUS_COMPLETED) {
					$formatted = sprintf("%s is finished working on the task %s%s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
							'<a href="'.$taskUrl.'">'.$taskTitle.'</a>',
							$predicate
						);						
				} elseif($status == Accepted::STATUS_PROGRESS) {
					$formatted = sprintf("%s is working on the task %s%s.",
							formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
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
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;	
			case 'create_update_comment':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem2ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s commented on the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;
			case 'create_update_comment_reply':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem3ID());
				$title = $update->getTitle();
				$url = Url::update($update->getID());
				$formatted = sprintf("%s replied to a comment on the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$url.'">'.$title.'</a>',
						$predicate
					);		
				break;
			case 'create_update':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s created the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;				
			case 'edit_update_title':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the title of the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;
			case 'edit_update_message':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the contents of the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;		
			case 'edit_update_uploads':
				$predicate = ($showProject) ? ' in the project '.formatProjectLink($event->getProjectID()) : '';
				$update = Update::load($event->getItem1ID());
				$updateTitle = $update->getTitle();
				$updateUrl = Url::update($update->getID());
				$formatted = sprintf("%s edited the attached files for the contribution %s%s.",
						formatUserLink($event->getUser1ID(), $event->getProjectID()),
						'<a href="'.$updateUrl.'">'.$updateTitle.'</a>',
						$predicate
					);					
				break;					
			default:
				$formatted = 'Event type "'.$event->getEventTypeID().'" not found.';
		}
		return $formatted;
}

function formatEventDetails($event) {
	$details = '';
	switch($event->getEventTypeID()) {
		case 'edit_update_uploads':
		case 'edit_task_uploads':
			$addedIDs = explode(',',$event->getData2());
			$added = '';
			foreach($addedIDs as $a) {
				if($a == '') continue; // skip blanks
				$upload = Upload::load($a);
				$added .= $upload->getOriginalName().' ('.formatFileSize($upload->getSize()).')<br /><br />';
			}
			if(!empty($added)) {
				$details .= '<ins>'.$added.'</ins>';
			}
			$deletedIDs = explode(',',$event->getData1());
			$deleted = '';
			foreach($deletedIDs as $d) {
				if($d == '') continue; // skip blanks
				$upload = Upload::load($d);
				$deleted .= $upload->getOriginalName().' ('.formatFileSize($upload->getSize()).')<br /><br />';
			}
			if(!empty($deleted)) {
				$details .= '<del>'.$deleted.'</del>';
			}
			break;
		case 'edit_pitch':	
		case 'edit_specs':
		case 'edit_rules':
		case 'edit_task_description':
		case 'edit_update_message':					
			$from = $event->getData1();
			$to = $event->getData2();	
			$from = str_replace('&#10;','<br />', $from);	
			$to = str_replace('&#10;','<br />', $to);	
			$diff = new FineDiff($from, $to);
			$htmlDiff = $diff->renderDiffToHTML();				
			$htmlDiff = html_entity_decode($htmlDiff, ENT_QUOTES, 'UTF-8');
			$htmlDiff = html_entity_decode($htmlDiff, ENT_QUOTES, 'UTF-8');
			$details .= $htmlDiff;	
			break;					
		case 'edit_task_title':
		case 'edit_update_title':
			$from = $event->getData1();
			$to = $event->getData2();	
			$diff = new FineDiff($from, $to);
			$htmlDiff = $diff->renderDiffToHTML();
			$htmlDiff = html_entity_decode($htmlDiff, ENT_QUOTES, 'UTF-8');
			$htmlDiff = html_entity_decode($htmlDiff, ENT_QUOTES, 'UTF-8');
			$details .= $htmlDiff;				
			break;
		case 'edit_task_leader':
			$details .= 'Old Leader: <del>'.formatUserLink($event->getUser1ID(), $event->getProjectID()).'</del><br /><br />';
			$details .= 'New Leader: <ins>'.formatUserLink($event->getUser2ID(), $event->getProjectID()).'</ins>';
			break;
		case 'edit_task_num_needed':
			$old = ($event->getData1() != null) ? $event->getData1() : '&#8734;';
			$new = ($event->getData2() != null) ? $event->getData2() : '&#8734;';				
			$details .= 'Old: <del>'.$old.'</del> people needed<br /><br />';
			$details .= 'New: <ins>'.$new.'</ins> people needed';
			break;		
		case 'edit_task_deadline':
		case 'edit_project_deadline':
			$old = ($event->getData1() != null) ? formatTimeTag($event->getData1()) : '(none)';
			$new = ($event->getData2() != null) ? formatTimeTag($event->getData2()) : '(none)';
			$details .= 'Old Deadline: <del>'.$old.'</del><br /><br />';
			$details .= 'New Deadline: <ins>'.$new.'</ins>';
			break;
		case 'edit_project_status':
			$old = formatProjectStatus($event->getData1());
			$new = formatProjectStatus($event->getData2());
			$details .= 'Old Project Status: <del>'.$old.'</del><br /><br />';
			$details .= 'New Project Status: <ins>'.$new.'</ins>';
			break;				
		case 'edit_accepted_status':
			$old = formatAcceptedStatus($event->getData1());
			$new = formatAcceptedStatus($event->getData2());
			$details .= 'Old Status: <del>'.$old.'</del><br /><br />';
			$details .= 'New Status: <ins>'.$new.'</ins>';
			break;
		case 'create_task_comment':
		case 'create_task_comment_reply':
		case 'create_update_comment':
		case 'create_update_comment_reply':
			$details .= formatComment($event->getData1());
			break;
		case 'create_discussion':
			$details .= '<strong>'.$event->getData1().'</strong><br /><br />';
			$details .= formatDiscussionReply($event->getData2());
			break;				
		case 'create_discussion_reply':
			$details .= formatDiscussionReply($event->getData1());
			break;
		case 'create_update':
			if($event->getData1() != '') {
				$details .= '<strong>'.$event->getData1().'</strong><br /><br />';
			}
			if($event->getData2() != '') {
				$details .= formatUpdate($event->getData2());
			}
			break;
		case 'create_task':
			if($event->getData1() != '') {
				$details .= '<strong>'.$event->getData1().'</strong><br /><br />';
			}
			if($event->getData2() != '') {
				$details .= formatTaskDescription($event->getData2());
			}
			break;
	}
	return ($details);
}
