-- phpMyAdmin SQL Dump
-- version 2.11.11.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 16, 2011 at 03:09 PM
-- Server version: 5.0.77
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted`
--

CREATE TABLE IF NOT EXISTS `accepted` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `creator_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `accepted`
--


-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `creator_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `task_id` int(10) unsigned default NULL,
  `update_id` int(10) unsigned default NULL,
  `parent_id` int(10) unsigned default NULL,
  `message` text NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`),
  KEY `parent_id` (`parent_id`),
  KEY `task_id` (`task_id`),
  KEY `update_id` (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `consent`
--

CREATE TABLE IF NOT EXISTS `consent` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `consent`
--


-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

CREATE TABLE IF NOT EXISTS `discussion` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `creator_id` int(11) unsigned NOT NULL,
  `project_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned default NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `category` tinyint(3) unsigned default NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `locked` tinyint(1) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `discussion`
--


-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `project_id` int(10) unsigned default NULL,
  `event_type_id` varchar(128) NOT NULL,
  `user_1_id` int(10) unsigned NOT NULL,
  `user_2_id` int(10) unsigned default NULL,
  `item_1_id` int(10) unsigned default NULL,
  `item_2_id` int(10) unsigned default NULL,
  `item_3_id` int(10) unsigned default NULL,
  `data_1` text,
  `data_2` text,
  `data_3` text,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `project_id` (`project_id`),
  KEY `event_type_id` (`event_type_id`),
  KEY `user_1_id` (`user_1_id`),
  KEY `user_2_id` (`user_2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `event`
--


-- --------------------------------------------------------

--
-- Table structure for table `event_type`
--

CREATE TABLE IF NOT EXISTS `event_type` (
  `id` varchar(128) NOT NULL,
  `description` varchar(255) default NULL,
  `group` tinyint(1) unsigned default NULL,
  `css_class` varchar(128) default NULL,
  `diffable` tinyint(1) unsigned NOT NULL default '0',
  `hidden` tinyint(1) unsigned NOT NULL default '0',
  `contribution` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_type`
--

INSERT INTO `event_type` (`id`, `description`, `group`, `css_class`, `diffable`, `hidden`, `contribution`) VALUES
('accept_member_invitation', NULL, 5, 'people', 0, 0, 0),
('accept_task', NULL, 3, 'task', 0, 0, 1),
('ban_user', NULL, 5, 'people', 0, 0, 0),
('create_discussion', NULL, 4, 'create-discussion', 1, 0, 1),
('create_discussion_reply', NULL, 4, 'create-discussion', 1, 0, 1),
('create_project', NULL, NULL, 'create', 0, 0, 1),
('create_task', NULL, 3, 'create-task', 0, 0, 1),
('create_task_comment', NULL, 3, 'create-comment', 1, 0, 1),
('create_task_comment_reply', NULL, 3, 'create-comment', 1, 0, 1),
('create_update', NULL, 3, 'create-update', 0, 0, 1),
('create_update_comment', NULL, 3, 'create-comment', 1, 0, 1),
('create_update_comment_reply', NULL, 3, 'create-comment', 1, 0, 1),
('create_user', NULL, NULL, 'people', 0, 0, 0),
('decline_member_invitation', NULL, 5, 'people', 0, 1, 0),
('edit_accepted_status', NULL, 3, 'edit-update', 1, 0, 1),
('edit_pitch', NULL, 2, 'edit-basics', 1, 0, 1),
('edit_project_deadline', NULL, 2, 'edit-basics', 1, 0, 1),
('edit_project_status', NULL, 2, 'edit-basics', 1, 0, 1),
('edit_rules', NULL, 2, 'edit-basics', 1, 0, 1),
('edit_specs', NULL, 2, 'edit-basics', 1, 0, 1),
('edit_task_deadline', NULL, 3, 'edit-task', 1, 0, 1),
('edit_task_description', NULL, 3, 'edit-task', 1, 0, 1),
('edit_task_leader', NULL, 3, 'edit-leader', 1, 0, 1),
('edit_task_num_needed', NULL, 3, 'edit-task', 1, 0, 1),
('edit_task_status', NULL, 3, 'edit-task', 0, 0, 1),
('edit_task_title', NULL, 3, 'edit-task', 1, 0, 1),
('edit_task_uploads', NULL, 3, 'edit-task', 1, 0, 1),
('edit_update_message', NULL, 3, 'edit-update', 1, 0, 1),
('edit_update_title', NULL, 3, 'edit-update', 1, 0, 1),
('edit_update_uploads', NULL, 3, 'edit-update', 1, 0, 0),
('follow_project', NULL, 5, 'people', 0, 0, 0),
('invite_member_email', NULL, 5, 'people', 0, 1, 0),
('invite_member_user', NULL, 5, 'people', 0, 1, 0),
('join_project', NULL, 5, 'people', 0, 0, 0),
('leave_project', NULL, 5, 'people', 0, 0, 0),
('lock_discussion', NULL, 4, 'lock-discussion', 0, 0, 0),
('read_message', NULL, NULL, NULL, 1, 1, 0),
('release_task', NULL, 3, 'task', 0, 0, 0),
('send_message', NULL, NULL, NULL, 1, 1, 0),
('trust_member', NULL, 5, 'people', 0, 0, 0),
('unban_user', NULL, 5, 'people', 0, 0, 0),
('unfollow_project', NULL, 5, 'people', 0, 1, 0),
('unlock_discussion', NULL, 4, 'unlock-discussion', 0, 0, 0),
('untrust_member', NULL, 5, 'people', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE IF NOT EXISTS `invitation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter_id` int(11) unsigned NOT NULL,
  `invitee_id` int(11) unsigned default NULL,
  `invitee_email` varchar(255) default NULL,
  `project_id` int(11) unsigned NOT NULL,
  `trusted` tinyint(3) unsigned NOT NULL default '0',
  `invitation_message` text,
  `response` tinyint(3) unsigned default NULL,
  `response_message` text,
  `date_responded` datetime default NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `inviter_id` (`inviter_id`),
  KEY `invitee_id` (`invitee_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invitation`
--


-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned default NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `date_sent` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `date_read` timestamp NULL default NULL,
  PRIMARY KEY  (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `message`
--


-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `creator_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(512) NOT NULL,
  `pitch` text NOT NULL,
  `specs` text,
  `rules` text,
  `status` tinyint(3) unsigned NOT NULL default '0',
  `deadline` datetime default NULL,
  `venue` varchar(255) default NULL,
  `private` tinyint(1) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `project`
--


-- --------------------------------------------------------

--
-- Table structure for table `project_user`
--

CREATE TABLE IF NOT EXISTS `project_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `relationship` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `project_user`
--


-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `creator_id` int(11) unsigned NOT NULL,
  `leader_id` int(10) unsigned default NULL,
  `project_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` datetime default NULL,
  `status` tinyint(3) unsigned NOT NULL default '0',
  `num_needed` int(10) unsigned default NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`),
  KEY `leader_id` (`leader_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `task`
--


-- --------------------------------------------------------

--
-- Table structure for table `update`
--

CREATE TABLE IF NOT EXISTS `update` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `creator_id` int(10) unsigned NOT NULL,
  `accepted_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`),
  KEY `accepted_id` (`accepted_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `update`
--


-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE IF NOT EXISTS `upload` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `creator_id` int(10) unsigned NOT NULL,
  `project_id` int(10) unsigned default NULL,
  `item_type` varchar(32) default NULL,
  `item_id` int(11) unsigned default NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `mime` varchar(128) default NULL,
  `size` bigint(20) unsigned default NULL,
  `height` int(11) default NULL,
  `width` int(11) default NULL,
  `deleted` tinyint(1) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `upload`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) default NULL,
  `dob` datetime default NULL,
  `sex` char(1) default NULL,
  `location` varchar(255) default NULL,
  `biography` text,
  `picture` varchar(128) default NULL,
  `picture_small` varchar(128) default NULL,
  `picture_large` varchar(128) default NULL,
  `notify_comment_task_leading` tinyint(4) unsigned NOT NULL default '1',
  `notify_edit_task_accepted` tinyint(4) unsigned NOT NULL default '1',
  `notify_comment_task_accepted` tinyint(4) unsigned NOT NULL default '1',
  `notify_comment_task_update` tinyint(4) unsigned NOT NULL default '1',
  `notify_invite_project` tinyint(4) unsigned NOT NULL default '1',
  `notify_trust_project` tinyint(4) unsigned NOT NULL default '1',
  `notify_banned_project` tinyint(4) unsigned NOT NULL default '1',
  `notify_discussion_started` tinyint(4) unsigned NOT NULL default '1',
  `notify_discussion_reply` tinyint(4) unsigned NOT NULL default '1',
  `notify_make_task_leader` tinyint(3) unsigned NOT NULL default '1',
  `notify_receive_message` tinyint(3) unsigned NOT NULL default '1',
  `notify_mass_email` tinyint(3) unsigned NOT NULL default '1',
  `admin` tinyint(3) unsigned NOT NULL default '0',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `user`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `accepted`
--
ALTER TABLE `accepted`
  ADD CONSTRAINT `accepted_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `accepted_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `accepted_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_5` FOREIGN KEY (`update_id`) REFERENCES `update` (`id`),
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`);

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `discussion_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `discussion_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `discussion` (`id`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_4` FOREIGN KEY (`event_type_id`) REFERENCES `event_type` (`id`),
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`user_1_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `event_ibfk_3` FOREIGN KEY (`user_2_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`inviter_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`invitee_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `invitation_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_user`
--
ALTER TABLE `project_user`
  ADD CONSTRAINT `project_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `project_user_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `task_ibfk_3` FOREIGN KEY (`leader_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `update`
--
ALTER TABLE `update`
  ADD CONSTRAINT `update_ibfk_10` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `update_ibfk_8` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `update_ibfk_9` FOREIGN KEY (`accepted_id`) REFERENCES `accepted` (`id`);

--
-- Constraints for table `upload`
--
ALTER TABLE `upload`
  ADD CONSTRAINT `upload_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `upload_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);
