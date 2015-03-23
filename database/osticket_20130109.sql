-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 09, 2013 at 10:00 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `osticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `ost_actor`
--

CREATE TABLE IF NOT EXISTS `ost_actor` (
  `actor_id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_info` text COLLATE utf8_unicode_ci,
  `type` tinyint(4) NOT NULL COMMENT '1: trader, 2:suspender, 3:CSD',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`actor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `ost_actor`
--

INSERT INTO `ost_actor` (`actor_id`, `actor_name`, `contact_info`, `type`, `status`, `updated`) VALUES
(1, 'Lê Thị Thùy Dương', 'Ben Tre', 1, 1, '2012-12-25 10:33:08'),
(2, 'Tín', 'Đồng Tháp', 2, 0, '2012-12-25 10:56:33'),
(3, 'Phương11', 'HCM', 2, 0, '2012-12-25 10:51:15'),
(4, 'Hằng ', '', 1, 1, '2012-12-25 10:41:14'),
(5, 'Trà My', '', 2, 1, '2012-12-25 11:21:45'),
(6, 'User 2', '', 3, 1, '2012-12-25 11:22:00'),
(8, 'quang', NULL, 1, 1, '2012-12-26 15:58:17'),
(10, 'phuong', 'HCM city', 1, 1, '2012-12-28 10:23:35'),
(11, 'Kos Dung', 'HCM city', 2, 1, '2012-12-28 10:23:19'),
(12, 'wiin', 'HCM city', 3, 1, '2012-12-28 10:22:53'),
(13, 'QuangLe', 'HCM city', 2, 1, '2012-12-28 10:22:36'),
(14, 'LewQuang', '', 1, 1, '2012-12-26 17:26:07'),
(15, 'vvvvvv', '', 3, 0, '2012-12-26 17:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `ost_api_key`
--

CREATE TABLE IF NOT EXISTS `ost_api_key` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `ipaddr` varchar(16) NOT NULL,
  `apikey` varchar(255) NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipaddr` (`ipaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_api_key`
--

INSERT INTO `ost_api_key` (`id`, `isactive`, `ipaddr`, `apikey`, `updated`, `created`) VALUES
(1, 1, '192.168.1.5', 'siri!', '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_attachment`
--

CREATE TABLE IF NOT EXISTS `ost_attachment` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `attachment_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `ost_attachment`
--

INSERT INTO `ost_attachment` (`attachment_id`, `attachment_name`, `ticket_id`, `updated`) VALUES
(6, 'cal.gif', 170, '2013-01-07 13:34:26'),
(7, 'poweredby.jpg', 170, '2013-01-07 13:34:26'),
(8, 'poweredby18.jpg', 171, '2013-01-07 16:50:03'),
(9, 'bg.gif', 172, '2013-01-08 14:42:01'),
(10, 'cal34.gif', 172, '2013-01-08 14:42:01'),
(11, 'fibres.png', 172, '2013-01-08 14:42:01'),
(12, 'home.gif', 172, '2013-01-08 14:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `ost_config`
--

CREATE TABLE IF NOT EXISTS `ost_config` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `isonline` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timezone_offset` float(3,1) NOT NULL DEFAULT '0.0',
  `enable_daylight_saving` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `staff_ip_binding` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `staff_max_logins` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `staff_login_timeout` int(10) unsigned NOT NULL DEFAULT '2',
  `staff_session_timeout` int(10) unsigned NOT NULL DEFAULT '30',
  `client_max_logins` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `client_login_timeout` int(10) unsigned NOT NULL DEFAULT '2',
  `client_session_timeout` int(10) unsigned NOT NULL DEFAULT '30',
  `max_page_size` tinyint(3) unsigned NOT NULL DEFAULT '25',
  `max_open_tickets` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `max_file_size` int(11) unsigned NOT NULL DEFAULT '1048576',
  `autolock_minutes` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `overdue_grace_period` int(10) unsigned NOT NULL DEFAULT '0',
  `alert_email_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `default_email_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `default_dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `default_priority_id` tinyint(2) unsigned NOT NULL DEFAULT '2',
  `default_template_id` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `default_smtp_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `spoof_default_smtp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `clickable_urls` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allow_priority_change` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `use_email_priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_captcha` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_auto_cron` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_mail_fetch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_email_piping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `send_sql_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `send_mailparse_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `send_login_errors` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `save_email_headers` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `strip_quoted_reply` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_ticket_activity` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_autoresponder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_autoresponder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_notice_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket_alert_admin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_alert_dept_members` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_alert_laststaff` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `message_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `message_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note_alert_laststaff` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `note_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `note_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overdue_alert_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overdue_alert_assigned` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `overdue_alert_dept_manager` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `overdue_alert_dept_members` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_assign_reopened_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_assigned_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_answered_tickets` tinyint(1) NOT NULL DEFAULT '0',
  `hide_staff_name` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `overlimit_notice_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email_attachments` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allow_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_email_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_online_attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_online_attachments_onlogin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `random_ticket_ids` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `log_level` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `log_graceperiod` int(10) unsigned NOT NULL DEFAULT '12',
  `upload_dir` varchar(255) NOT NULL DEFAULT '',
  `allowed_filetypes` varchar(255) NOT NULL DEFAULT '.doc, .pdf',
  `time_format` varchar(32) NOT NULL DEFAULT ' h:i A',
  `date_format` varchar(32) NOT NULL DEFAULT 'm/d/Y',
  `datetime_format` varchar(60) NOT NULL DEFAULT 'm/d/Y g:i a',
  `daydatetime_format` varchar(60) NOT NULL DEFAULT 'D, M j Y g:ia',
  `reply_separator` varchar(60) NOT NULL DEFAULT '-- do not edit --',
  `admin_email` varchar(125) NOT NULL DEFAULT '',
  `helpdesk_title` varchar(255) NOT NULL DEFAULT 'osTicket Support Ticket System',
  `helpdesk_url` varchar(255) NOT NULL DEFAULT '',
  `api_passphrase` varchar(125) NOT NULL DEFAULT '',
  `ostversion` varchar(16) NOT NULL DEFAULT '',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `isoffline` (`isonline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_config`
--

INSERT INTO `ost_config` (`id`, `isonline`, `timezone_offset`, `enable_daylight_saving`, `staff_ip_binding`, `staff_max_logins`, `staff_login_timeout`, `staff_session_timeout`, `client_max_logins`, `client_login_timeout`, `client_session_timeout`, `max_page_size`, `max_open_tickets`, `max_file_size`, `autolock_minutes`, `overdue_grace_period`, `alert_email_id`, `default_email_id`, `default_dept_id`, `default_priority_id`, `default_template_id`, `default_smtp_id`, `spoof_default_smtp`, `clickable_urls`, `allow_priority_change`, `use_email_priority`, `enable_captcha`, `enable_auto_cron`, `enable_mail_fetch`, `enable_email_piping`, `send_sql_errors`, `send_mailparse_errors`, `send_login_errors`, `save_email_headers`, `strip_quoted_reply`, `log_ticket_activity`, `ticket_autoresponder`, `message_autoresponder`, `ticket_notice_active`, `ticket_alert_active`, `ticket_alert_admin`, `ticket_alert_dept_manager`, `ticket_alert_dept_members`, `message_alert_active`, `message_alert_laststaff`, `message_alert_assigned`, `message_alert_dept_manager`, `note_alert_active`, `note_alert_laststaff`, `note_alert_assigned`, `note_alert_dept_manager`, `overdue_alert_active`, `overdue_alert_assigned`, `overdue_alert_dept_manager`, `overdue_alert_dept_members`, `auto_assign_reopened_tickets`, `show_assigned_tickets`, `show_answered_tickets`, `hide_staff_name`, `overlimit_notice_active`, `email_attachments`, `allow_attachments`, `allow_email_attachments`, `allow_online_attachments`, `allow_online_attachments_onlogin`, `random_ticket_ids`, `log_level`, `log_graceperiod`, `upload_dir`, `allowed_filetypes`, `time_format`, `date_format`, `datetime_format`, `daydatetime_format`, `reply_separator`, `admin_email`, `helpdesk_title`, `helpdesk_url`, `api_passphrase`, `ostversion`, `updated`) VALUES
(1, 1, 7.0, 0, 1, 4, 2, 30, 4, 2, 30, 25, 5, 1048576, 3, 0, 2, 1, 1, 2, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 2, 12, '', '.doc, .pdf', ' h:i A', 'm/d/Y', 'm/d/Y g:i a', 'D, M j Y g:ia', '-- do not edit --', 'alice.le@nexcel.vn', 'osTicket :: Support Ticket System', 'http://localhost/osticket/upload/', '', '1.6 ST', '2012-12-19 06:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_department`
--

CREATE TABLE IF NOT EXISTS `ost_department` (
  `dept_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email_id` int(10) unsigned NOT NULL DEFAULT '0',
  `autoresp_email_id` int(10) unsigned NOT NULL DEFAULT '0',
  `manager_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dept_name` varchar(32) NOT NULL DEFAULT '',
  `dept_signature` tinytext NOT NULL,
  `ispublic` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ticket_auto_response` tinyint(1) NOT NULL DEFAULT '1',
  `message_auto_response` tinyint(1) NOT NULL DEFAULT '0',
  `can_append_signature` tinyint(1) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`dept_id`),
  UNIQUE KEY `dept_name` (`dept_name`),
  KEY `manager_id` (`manager_id`),
  KEY `autoresp_email_id` (`autoresp_email_id`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ost_department`
--

INSERT INTO `ost_department` (`dept_id`, `tpl_id`, `email_id`, `autoresp_email_id`, `manager_id`, `dept_name`, `dept_signature`, `ispublic`, `ticket_auto_response`, `message_auto_response`, `can_append_signature`, `updated`, `created`) VALUES
(1, 0, 1, 0, 0, 'Support', 'Support Dept', 1, 1, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(2, 0, 1, 0, 0, 'Billing', 'Billing Dept', 1, 1, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(3, 0, 1, 0, 0, 'R&D Team', 'R&D team', 1, 1, 1, 0, '2012-12-19 14:41:07', '2012-12-19 14:41:07'),
(6, 0, 2, 0, 0, 'werw 234', 'wswer', 1, 1, 1, 0, '2012-12-24 14:31:56', '2012-12-24 13:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `ost_email`
--

CREATE TABLE IF NOT EXISTS `ost_email` (
  `email_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `noautoresp` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priority_id` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(125) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `userid` varchar(125) NOT NULL,
  `userpass` varchar(125) NOT NULL,
  `mail_active` tinyint(1) NOT NULL DEFAULT '0',
  `mail_host` varchar(125) NOT NULL,
  `mail_protocol` enum('POP','IMAP') NOT NULL DEFAULT 'POP',
  `mail_encryption` enum('NONE','SSL') NOT NULL,
  `mail_port` int(6) DEFAULT NULL,
  `mail_fetchfreq` tinyint(3) NOT NULL DEFAULT '5',
  `mail_fetchmax` tinyint(4) NOT NULL DEFAULT '30',
  `mail_delete` tinyint(1) NOT NULL DEFAULT '0',
  `mail_errors` tinyint(3) NOT NULL DEFAULT '0',
  `mail_lasterror` datetime DEFAULT NULL,
  `mail_lastfetch` datetime DEFAULT NULL,
  `smtp_active` tinyint(1) DEFAULT '0',
  `smtp_host` varchar(125) NOT NULL,
  `smtp_port` int(6) DEFAULT NULL,
  `smtp_secure` tinyint(1) NOT NULL DEFAULT '1',
  `smtp_auth` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`email_id`),
  UNIQUE KEY `email` (`email`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ost_email`
--

INSERT INTO `ost_email` (`email_id`, `noautoresp`, `priority_id`, `dept_id`, `email`, `name`, `userid`, `userpass`, `mail_active`, `mail_host`, `mail_protocol`, `mail_encryption`, `mail_port`, `mail_fetchfreq`, `mail_fetchmax`, `mail_delete`, `mail_errors`, `mail_lasterror`, `mail_lastfetch`, `smtp_active`, `smtp_host`, `smtp_port`, `smtp_secure`, `smtp_auth`, `created`, `updated`) VALUES
(1, 0, 2, 1, 'luckystar2203@gmail.com', 'Support', 'luckystar2203', 'lbbbk5dimBd0DQwn/RwCm4miR12t+FVPS3C+zFV9a2g=', 0, 'pop.gmail.com', 'POP', 'NONE', 995, 5, 30, 0, 0, NULL, NULL, 0, 'smtp.gmail.com', 465, 1, 1, '2012-12-19 13:13:44', '2013-01-03 13:36:32'),
(2, 0, 1, 1, 'alerts@gmail.com', 'osTicket Alerts', '', '', 0, '', 'POP', 'NONE', NULL, 5, 30, 0, 0, NULL, NULL, 0, '', NULL, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(3, 0, 1, 1, 'noreply@gmail.com', '', '', '', 0, '', 'POP', 'NONE', NULL, 5, 30, 0, 0, NULL, NULL, 0, '', NULL, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_email_banlist`
--

CREATE TABLE IF NOT EXISTS `ost_email_banlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `submitter` varchar(126) NOT NULL DEFAULT '',
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_email_banlist`
--

INSERT INTO `ost_email_banlist` (`id`, `email`, `submitter`, `added`) VALUES
(1, 'test@example.com', 'System', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_email_template`
--

CREATE TABLE IF NOT EXISTS `ost_email_template` (
  `tpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `cfg_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  `notes` text,
  `ticket_autoresp_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_autoresp_body` text NOT NULL,
  `ticket_notice_subj` varchar(255) NOT NULL,
  `ticket_notice_body` text NOT NULL,
  `ticket_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_alert_body` text NOT NULL,
  `message_autoresp_subj` varchar(255) NOT NULL DEFAULT '',
  `message_autoresp_body` text NOT NULL,
  `message_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `message_alert_body` text NOT NULL,
  `note_alert_subj` varchar(255) NOT NULL,
  `note_alert_body` text NOT NULL,
  `assigned_alert_subj` varchar(255) NOT NULL DEFAULT '',
  `assigned_alert_body` text NOT NULL,
  `ticket_overdue_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_overdue_body` text NOT NULL,
  `ticket_overlimit_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_overlimit_body` text NOT NULL,
  `ticket_reply_subj` varchar(255) NOT NULL DEFAULT '',
  `ticket_reply_body` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tpl_id`),
  KEY `cfg_id` (`cfg_id`),
  FULLTEXT KEY `message_subj` (`ticket_reply_subj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ost_email_template`
--

INSERT INTO `ost_email_template` (`tpl_id`, `cfg_id`, `name`, `notes`, `ticket_autoresp_subj`, `ticket_autoresp_body`, `ticket_notice_subj`, `ticket_notice_body`, `ticket_alert_subj`, `ticket_alert_body`, `message_autoresp_subj`, `message_autoresp_body`, `message_alert_subj`, `message_alert_body`, `note_alert_subj`, `note_alert_body`, `assigned_alert_subj`, `assigned_alert_body`, `ticket_overdue_subj`, `ticket_overdue_body`, `ticket_overlimit_subj`, `ticket_overlimit_body`, `ticket_reply_subj`, `ticket_reply_body`, `created`, `updated`) VALUES
(1, 1, 'osTicket Default Template', 'Default osTicket templates', 'Support Ticket Opened [#%ticket]', '%name,\r\n\r\nA request for support has been created and assigned ticket #%ticket. A representative will follow-up with you as soon as possible.\r\n\r\nYou can view this ticket''s progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\nIf you wish to send additional comments or information regarding this issue, please don''t open a new ticket. Simply login using the link above and update the ticket.\r\n\r\n%signature', '[#%ticket] %subject', '%name,\r\n\r\nOur customer care team has created a ticket, #%ticket on your behalf, with the following message.\r\n\r\n%message\r\n\r\nIf you wish to provide additional comments or information regarding this issue, please don''t open a new ticket. You can update or view this ticket''s progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'New Ticket Alert', '%staff,\r\n\r\nNew ticket #%ticket created.\r\n-------------------\r\nName: %name\r\nEmail: %email\r\nDept: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', '[#%ticket] Message Added', '%name,\r\n\r\nYour reply to support request #%ticket has been noted.\r\n\r\nYou can view this support request progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'New Message Alert', '%staff,\r\n\r\nNew message appended to ticket #%ticket\r\n\r\n----------------------\r\nName: %name\r\nEmail: %email\r\nDept: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', 'New Internal Note Alert', '%staff,\r\n\r\nInternal note appended to ticket #%ticket\r\n\r\n----------------------\r\nName: %name\r\n\r\n%note\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', 'Ticket #%ticket Assigned to you', '%assignee,\r\n\r\n%assigner has assigned ticket #%ticket to you!\r\n\r\n%message\r\n\r\nTo view complete details, simply login to the support system.\r\n\r\n- Your friendly Support Ticket System - powered by osTicket.', 'Stale Ticket Alert', '%staff,\r\n\r\nA ticket, #%ticket assigned to you or in your department is seriously overdue.\r\n\r\n%url/scp/tickets.php?id=%id\r\n\r\nWe should all work hard to guarantee that all tickets are being addressed in a timely manner. Enough baby talk...please address the issue or you will hear from me again.\r\n\r\n\r\n- Your friendly (although with limited patience) Support Ticket System - powered by osTicket.', 'Support Ticket Denied', '%name\r\n\r\nNo support ticket has been created. You''ve exceeded maximum number of open tickets allowed.\r\n\r\nThis is a temporary block. To be able to open another ticket, one of your pending tickets must be closed. To update or add comments to an open ticket simply login using the link below.\r\n\r\n%url/view.php?e=%email\r\n\r\nThank you.\r\n\r\nSupport Ticket System', '[#%ticket] %subject', '%name,\r\n\r\nA customer support staff member has replied to your support request, #%ticket with the following response:\r\n\r\n%response\r\n\r\nWe hope this response has sufficiently answered your questions. If not, please do not send another email. Instead, reply to this email or login to your account for a complete archive of all your support requests and responses.\r\n\r\n%url/view.php?e=%email&t=%ticket\r\n\r\n%signature', '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_groups`
--

CREATE TABLE IF NOT EXISTS `ost_groups` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `group_name` varchar(50) NOT NULL DEFAULT '',
  `dept_access` varchar(255) NOT NULL DEFAULT '',
  `can_create_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_edit_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_delete_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_close_tickets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_transfer_tickets` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `can_ban_emails` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_manage_kb` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`group_id`),
  KEY `group_active` (`group_enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ost_groups`
--

INSERT INTO `ost_groups` (`group_id`, `group_enabled`, `group_name`, `dept_access`, `can_create_tickets`, `can_edit_tickets`, `can_delete_tickets`, `can_close_tickets`, `can_transfer_tickets`, `can_ban_emails`, `can_manage_kb`, `created`, `updated`) VALUES
(1, 1, 'Admins', '1', 1, 1, 1, 1, 1, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(2, 1, 'Managers', '1', 1, 1, 0, 1, 1, 1, 1, '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(3, 1, 'Staff', '1', 1, 0, 0, 0, 0, 0, 0, '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_help_topic`
--

CREATE TABLE IF NOT EXISTS `ost_help_topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `isactive` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `noautoresp` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `priority_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dept_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `topic` varchar(32) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `topic` (`topic`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ost_help_topic`
--

INSERT INTO `ost_help_topic` (`topic_id`, `isactive`, `noautoresp`, `priority_id`, `dept_id`, `topic`, `created`, `updated`) VALUES
(1, 1, 0, 2, 1, 'Support', '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(2, 1, 0, 3, 1, 'Billing', '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_kb_premade`
--

CREATE TABLE IF NOT EXISTS `ost_kb_premade` (
  `premade_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0',
  `isenabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `title` varchar(125) NOT NULL DEFAULT '',
  `answer` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`premade_id`),
  UNIQUE KEY `title_2` (`title`),
  KEY `dept_id` (`dept_id`),
  KEY `active` (`isenabled`),
  FULLTEXT KEY `title` (`title`,`answer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ost_kb_premade`
--

INSERT INTO `ost_kb_premade` (`premade_id`, `dept_id`, `isenabled`, `title`, `answer`, `created`, `updated`) VALUES
(1, 0, 1, 'What is osTicket (sample)?', '\r\nosTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.', '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(2, 0, 1, 'Sample (with variables)', '\r\n%name,\r\n\r\nYour ticket #%ticket created on %createdate is in %dept department.\r\n\r\n', '2012-12-19 13:13:44', '2012-12-19 13:13:44');

-- --------------------------------------------------------

--
-- Table structure for table `ost_league`
--

CREATE TABLE IF NOT EXISTS `ost_league` (
  `league_id` int(11) NOT NULL AUTO_INCREMENT,
  `league_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `ibc_league_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`league_id`),
  KEY `sport_type` (`sport_type`,`ibc_league_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `ost_league`
--

INSERT INTO `ost_league` (`league_id`, `league_name`, `sport_type`, `ibc_league_id`, `status`, `updated`) VALUES
(47, 'league1', 1, 123, 1, '2013-01-03 17:02:39'),
(48, 'League', 3, 12123, 1, '2013-01-03 17:03:32'),
(49, 'League', 3, 12123, 0, '2013-01-03 17:07:46'),
(50, 'League 3', 4, 12123, 1, '2013-01-04 10:48:51'),
(51, 'League 4', 4, 1123, 1, '2013-01-04 14:52:44'),
(52, 'league 5', 3, 123123, 1, '2013-01-04 15:27:42'),
(53, 'qwe12312', 4, 131, 0, '2013-01-07 10:40:16'),
(54, 'qwe12', 6, 123, 0, '2013-01-07 16:48:43'),
(55, 'qweqwe', 6, 12, 0, '2013-01-07 16:52:12');

-- --------------------------------------------------------

--
-- Table structure for table `ost_match`
--

CREATE TABLE IF NOT EXISTS `ost_match` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `home_id` int(11) NOT NULL,
  `away_id` int(11) NOT NULL,
  `ibc_match_id` int(11) DEFAULT NULL,
  `event_date` date NOT NULL,
  `match_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`match_id`),
  KEY `league_id` (`league_id`),
  KEY `league_id_2` (`league_id`,`home_id`,`away_id`,`ibc_match_id`,`sport_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `ost_match`
--

INSERT INTO `ost_match` (`match_id`, `league_id`, `home_id`, `away_id`, `ibc_match_id`, `event_date`, `match_code`, `sport_type`, `status`, `updated`) VALUES
(2, 50, 1, 3, 12332, '2013-01-19', '123adsa', 4, 0, '2013-01-04 12:42:06'),
(11, 50, 1, 3, 0, '2013-01-25', 'code1', 4, 0, '2013-01-04 16:55:10'),
(12, 50, 1, 3, 0, '2013-01-17', 'qweqwe', 4, 1, '2013-01-04 16:59:40'),
(13, 51, 1, 3, 0, '2013-01-16', 'wew', 4, 1, '2013-01-07 08:58:11'),
(14, 50, 3, 1, 0, '2013-01-15', '1231', 4, 1, '2013-01-07 10:47:50'),
(15, 51, 3, 1, 111, '2013-01-11', '111', 4, 1, '2013-01-07 11:07:45'),
(16, 50, 3, 1, 0, '2013-01-18', '111', 4, 1, '2013-01-07 11:29:25'),
(17, 50, 3, 1, 11, '2013-01-10', '111', 4, 1, '2013-01-07 12:40:31'),
(18, 50, 3, 1, 0, '2013-01-09', '111', 4, 1, '2013-01-07 12:41:33'),
(19, 50, 3, 1, 13123, '1969-12-31', '123123', 4, 1, '2013-01-07 13:13:24'),
(23, 50, 3, 1, 123, '2013-02-14', 'q121', 4, 1, '2013-01-07 13:41:05'),
(24, 50, 1, 3, 12, '2013-01-30', 'qw', 4, 1, '2013-01-07 17:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `ost_sport`
--

CREATE TABLE IF NOT EXISTS `ost_sport` (
  `sport_type` int(11) NOT NULL AUTO_INCREMENT,
  `sport_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`sport_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ost_sport`
--

INSERT INTO `ost_sport` (`sport_type`, `sport_name`, `status`, `updated`) VALUES
(1, 'Football', 1, '2013-01-03 00:00:00'),
(3, 'Basket-ball', 1, '2013-01-03 15:18:05'),
(4, 'Boxing', 1, '2013-01-03 15:32:29'),
(6, 'swimming', 1, '2013-01-04 13:22:28'),
(7, 'qweqweqwe', 0, '2013-01-07 13:19:13'),
(8, 'asdad', 0, '2013-01-07 16:48:13'),
(9, 'qw12312', 0, '2013-01-07 16:52:43'),
(10, 'qw1231234', 0, '2013-01-07 16:55:18'),
(11, 'qw12312341213', 0, '2013-01-07 16:58:29');

-- --------------------------------------------------------

--
-- Table structure for table `ost_staff`
--

CREATE TABLE IF NOT EXISTS `ost_staff` (
  `staff_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(32) NOT NULL DEFAULT '',
  `firstname` varchar(32) DEFAULT NULL,
  `lastname` varchar(32) DEFAULT NULL,
  `passwd` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone` varchar(24) NOT NULL DEFAULT '',
  `phone_ext` varchar(6) DEFAULT NULL,
  `mobile` varchar(24) NOT NULL DEFAULT '',
  `signature` tinytext NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `isvisible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `onvacation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `daylight_saving` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `append_signature` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `change_passwd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timezone_offset` float(3,1) NOT NULL DEFAULT '0.0',
  `max_page_size` int(11) unsigned NOT NULL DEFAULT '0',
  `auto_refresh_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastlogin` datetime DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `username` (`username`),
  KEY `dept_id` (`dept_id`),
  KEY `issuperuser` (`isadmin`),
  KEY `group_id` (`group_id`,`staff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ost_staff`
--

INSERT INTO `ost_staff` (`staff_id`, `group_id`, `dept_id`, `username`, `firstname`, `lastname`, `passwd`, `email`, `phone`, `phone_ext`, `mobile`, `signature`, `isactive`, `isadmin`, `isvisible`, `onvacation`, `daylight_saving`, `append_signature`, `change_passwd`, `timezone_offset`, `max_page_size`, `auto_refresh_rate`, `created`, `lastlogin`, `updated`) VALUES
(1, 1, 1, 'luckystar2203', 'Admin', 'Admin', 'e10adc3949ba59abbe56e057f20f883e', 'alice.le@nexcel.vn', '', NULL, '', '', 1, 1, 1, 0, 0, 0, 0, 7.0, 25, 0, '2012-12-19 13:13:44', '2013-01-09 16:55:48', '2013-01-08 10:50:01'),
(2, 3, 2, 'user1', 'user1', 'user1', 'e10adc3949ba59abbe56e057f20f883e', 'alice.le@nexcel.vn', '', '', '', '', 1, 0, 1, 0, 0, 0, 0, 0.0, 0, 0, '2012-12-19 14:21:47', '2012-12-26 14:51:12', '2012-12-31 09:23:43'),
(3, 3, 3, 'user2', 'user2', 'user2', 'e10adc3949ba59abbe56e057f20f883e', 'cobedoihon_2203@yahoo.com', '', '', '', '', 1, 0, 1, 0, 0, 0, 0, 0.0, 0, 0, '2012-12-19 15:04:43', '2012-12-26 14:49:24', '2012-12-31 09:48:44'),
(4, 1, 3, 'anders', 'Anders', 'Quang', '1b4e689adc0aace535c9dcfec3b3c351', 'anders@nexcel.com', '', '', '', '', 1, 1, 1, 0, 0, 0, 0, 0.0, 0, 0, '2012-12-26 15:06:17', '2012-12-28 13:02:32', '2012-12-26 15:08:31'),
(5, 1, 3, 'kos', 'Kos', 'Dung', '1b4e689adc0aace535c9dcfec3b3c351', 'kos@nexcel.com', '', '', '', '', 1, 1, 1, 0, 0, 0, 0, 0.0, 0, 0, '2012-12-26 15:07:01', '2012-12-26 15:26:22', '2012-12-26 15:26:37');

-- --------------------------------------------------------

--
-- Table structure for table `ost_syslog`
--

CREATE TABLE IF NOT EXISTS `ost_syslog` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` enum('Debug','Warning','Error') NOT NULL,
  `title` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `logger` varchar(64) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`),
  KEY `log_type` (`log_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=226 ;

--
-- Dumping data for table `ost_syslog`
--

INSERT INTO `ost_syslog` (`log_id`, `log_type`, `title`, `log`, `logger`, `ip_address`, `created`, `updated`) VALUES
(1, 'Debug', 'osTicket installed!', 'Congratulations osTicket basic installation completed!\n\nThank you for choosing osTicket!', '', '::1', '2012-12-19 13:13:44', '2012-12-19 13:13:44'),
(2, 'Warning', 'Failed login attempt (client)', 'Email: user1@gmail.com\nTicket #: 194270\nIP: ::1\nTIME: Dec 20, 2012, 2:49 am UTC\n\nAttempts #2', '', '::1', '2012-12-20 09:49:43', '2012-12-20 09:49:43'),
(3, 'Error', 'DB Error #1064', '[INSERT INTO ost_ticket SET created=NOW() ,ticketID=222801,dept_id=3,topic_id=1,priority_id=3,email=''user3@gmail.com'',name=''duong 3'',subject=''test 333333'',league=123,match=345,trader=678,suspender=901,csd=345,helptopic=''Support'',phone="",phone_ext='''',ip_address=''::1'',source=''Phone'',duedate=''2012-12-28 0:00'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''match=345,trader=678,suspender=901,csd=345,helptopic=''Support'',phone="",phone_ex'' at line 1', '', '::1', '2012-12-20 16:14:00', '2012-12-20 16:14:00'),
(4, 'Error', 'DB Error #1064', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,match,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attach_id) as attachments  ,count(attach.attach_id) as attachments, IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  LEFT JOIN ost_ticket_attachment attach ON  ticket.ticket_id=attach.ticket_id   WHERE 1 AND status=''open'' AND isanswered=0   GROUP BY ticket.ticket_id ORDER BY priority_urgency,effective_date DESC ,ticket.created DESC LIMIT 0,25]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue'' at line 1', '', '::1', '2012-12-20 16:56:55', '2012-12-20 16:56:55'),
(5, 'Error', 'DB Error #1064', '[UPDATE ost_ticket SET updated=NOW() ,email=''user3@gmail.com'',name=''duong 3'',subject=''test 333333'',league=1231111111111,match=345,trader=678,suspender=678,csd=345,phone="",phone_ext='''',priority_id=3,topic_id=1,duedate=''2012-12-28 0:00'',isoverdue=0,helptopic=''Support'' WHERE ticket_id=6]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''match=345,trader=678,suspender=678,csd=345,phone="",phone_ext='''',priority_id=3,t'' at line 1', '', '::1', '2012-12-20 17:07:46', '2012-12-20 17:07:46'),
(6, 'Error', 'DB Error #1054', '[SELECT league_id,league_name FROM ost_staff WHERE status=1 ORDER BY league_name]\n\nUnknown column ''league_id'' in ''field list''', '', '::1', '2012-12-21 17:36:22', '2012-12-21 17:36:22'),
(7, 'Error', 'DB Error #1054', '[SELECT league_id,league_name FROM ost_staff WHERE status=1 ORDER BY league_name]\n\nUnknown column ''league_id'' in ''field list''', '', '::1', '2012-12-21 17:36:59', '2012-12-21 17:36:59'),
(8, 'Error', 'DB Error #1054', '[SELECT league_id,league_name FROM ost_staff WHERE status=1 ORDER BY league_name]\n\nUnknown column ''league_id'' in ''field list''', '', '::1', '2012-12-21 17:37:23', '2012-12-21 17:37:23'),
(9, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  like ''%''asa''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''asa''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:47:22', '2012-12-24 12:47:22'),
(10, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  like ''%''asa''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''asa''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:47:23', '2012-12-24 12:47:23'),
(11, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  like ''%''le''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''le''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:47:35', '2012-12-24 12:47:35'),
(12, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  like ''%''le''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''le''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:47:36', '2012-12-24 12:47:36'),
(13, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  like ''%''League''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''League''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:47:47', '2012-12-24 12:47:47'),
(14, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  like ''%''League''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%''League''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:47:48', '2012-12-24 12:47:48'),
(15, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  like ''%2%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%2%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:47:56', '2012-12-24 12:47:56'),
(16, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  like ''%2%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%2%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:47:57', '2012-12-24 12:47:57'),
(17, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  like ''%2%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%2%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:48:36', '2012-12-24 12:48:36'),
(18, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  like ''%2%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''like ''%2%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:48:37', '2012-12-24 12:48:37'),
(19, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''ea''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ea''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:50:12', '2012-12-24 12:50:12'),
(20, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''ea''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ea''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:50:13', '2012-12-24 12:50:13'),
(21, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''Le''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:50:21', '2012-12-24 12:50:21'),
(22, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''Le''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:50:22', '2012-12-24 12:50:22'),
(23, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''Le''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:50:35', '2012-12-24 12:50:35'),
(24, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''Le''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:50:36', '2012-12-24 12:50:36'),
(25, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''Le''%'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'''' at line 1', '', '127.0.0.1', '2012-12-24 12:50:50', '2012-12-24 12:50:50'),
(26, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''Le''%'' ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'' ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:50:51', '2012-12-24 12:50:51'),
(27, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''Le''%'' and status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'' and status=1'' at line 1', '', '127.0.0.1', '2012-12-24 12:51:29', '2012-12-24 12:51:29'),
(28, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''Le''%'' and status=1 ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''Le''%'' and status=1 ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:51:30', '2012-12-24 12:51:30'),
(29, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_league  WHERE league_name like ''%''League 1''%'' and status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''League 1''%'' and status=1'' at line 1', '', '127.0.0.1', '2012-12-24 12:51:49', '2012-12-24 12:51:49'),
(30, 'Error', 'DB Error #1064', '[SELECT league_id,league_name   FROM ost_league  WHERE league_name like ''%''League 1''%'' and status=1 ORDER BY league_name DESC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''League 1''%'' and status=1 ORDER BY league_name DESC LIMIT 0,30'' at line 1', '', '127.0.0.1', '2012-12-24 12:51:50', '2012-12-24 12:51:50'),
(31, 'Error', 'DB Error #1064', '[INSERT INTO ost_league  SET league_name=''''454561 werw'''',status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''454561 werw'''',status=1'' at line 1', '', '127.0.0.1', '2012-12-24 13:15:26', '2012-12-24 13:15:26'),
(32, 'Error', 'DB Error #1064', '[INSERT INTO ost_league  SET league_name=''''asd asdd'''',status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''asd asdd'''',status=1'' at line 1', '', '127.0.0.1', '2012-12-24 13:15:49', '2012-12-24 13:15:49'),
(33, 'Error', 'DB Error #1064', '[INSERT INTO ost_league  SET league_name=''''eqwe'''',status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''eqwe'''',status=1'' at line 1', '', '127.0.0.1', '2012-12-24 13:27:22', '2012-12-24 13:27:22'),
(34, 'Error', 'DB Error #1064', '[SELECT match_id,match_name,status,CASE WHEN league_id!=''''\r\n                THEN\r\n                SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    WHERE lea.league_id=ma.league_id \r\n                ELSE\r\n              END AS league_name  FROM ost_match  WHERE status=1 ORDER BY updated DESC,match_name ASC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    '' at line 3', '', '127.0.0.1', '2012-12-24 15:34:33', '2012-12-24 15:34:33'),
(35, 'Error', 'DB Error #1064', '[SELECT match_id,match_name,status,CASE WHEN league_id!=''''\r\n                THEN\r\n                SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    WHERE lea.league_id=ma.league_id \r\n                ELSE\r\n              END AS league_name  FROM ost_match  WHERE status=1 ORDER BY updated DESC,match_name ASC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    '' at line 3', '', '127.0.0.1', '2012-12-24 15:34:58', '2012-12-24 15:34:58'),
(36, 'Error', 'DB Error #1064', '[SELECT match_id,match_name,status,CASE league_id\r\n             WHEN league_id!=''''\r\n             THEN\r\n                SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    WHERE lea.league_id=ma.league_id \r\n             ELSE ''''\r\n             END AS league_name  FROM ost_match  WHERE status=1 ORDER BY updated DESC,match_name ASC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''SELECT league_name FROM ost_league AS lea, ost_match AS ma\r\n                    '' at line 4', '', '127.0.0.1', '2012-12-24 15:37:41', '2012-12-24 15:37:41'),
(37, 'Error', 'DB Error #1064', '[SELECT match_id,match_name,status,CASE league_id\r\n             WHEN league_id!=''''\r\n             THEN\r\n                SELECT league_name FROM ost_league AS lea\r\n                    WHERE lea.league_id=league_id \r\n             ELSE ''''\r\n             END AS league_name  FROM ost_match  WHERE status=1 ORDER BY updated DESC,match_name ASC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''SELECT league_name FROM ost_league AS lea\r\n                    WHERE lea.league_'' at line 4', '', '127.0.0.1', '2012-12-24 15:45:08', '2012-12-24 15:45:08'),
(38, 'Error', 'DB Error #1064', '[SELECT match_id,match_name,status,CASE ma.league_id\r\n             WHEN ma.league_id!=''''\r\n             THEN\r\n                SELECT league_name FROM ost_league AS lea\r\n                    WHERE lea.league_id=ma.league_id \r\n             ELSE ''''\r\n             END AS league_name  FROM ost_match AS ma WHERE status=1 ORDER BY updated DESC,match_name ASC LIMIT 0,30]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''SELECT league_name FROM ost_league AS lea\r\n                    WHERE lea.league_'' at line 4', '', '127.0.0.1', '2012-12-24 15:49:27', '2012-12-24 15:49:27'),
(39, 'Error', 'DB Error #1064', '[SELECT league_id,league_name FROM ost_league WHERE status=1 ORDER BY ]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '127.0.0.1', '2012-12-24 16:59:23', '2012-12-24 16:59:23'),
(40, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%__%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:04:10', '2012-12-25 09:04:10'),
(41, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%__%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:04:11', '2012-12-25 09:04:11'),
(42, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%__%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:05:42', '2012-12-25 09:05:42'),
(43, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%__%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:05:43', '2012-12-25 09:05:43'),
(44, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%1%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:05:50', '2012-12-25 09:05:50'),
(45, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%1%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:05:51', '2012-12-25 09:05:51'),
(46, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%1%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:06:11', '2012-12-25 09:06:11'),
(47, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%1%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:06:12', '2012-12-25 09:06:12'),
(48, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%Match 1%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:06:34', '2012-12-25 09:06:34'),
(49, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%Match 1%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:06:35', '2012-12-25 09:06:35'),
(50, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%Match 1%'' and status=1 AND ma.status=1]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:07:13', '2012-12-25 09:07:13'),
(51, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND match_name like ''%Match 1%'' and status=1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2012-12-25 09:07:14', '2012-12-25 09:07:14'),
(52, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:25', '2012-12-25 09:12:25'),
(53, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:26', '2012-12-25 09:12:26'),
(54, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''2'' AND ma.status=1]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:33', '2012-12-25 09:12:33'),
(55, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''2'' AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:34', '2012-12-25 09:12:34'),
(56, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:44', '2012-12-25 09:12:44'),
(57, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:45', '2012-12-25 09:12:45'),
(58, 'Error', 'DB Error #1052', '[SELECT count(*)  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:58', '2012-12-25 09:12:58'),
(59, 'Error', 'DB Error #1052', '[SELECT match_id,match_name,ma.status,ma.league_id,league_name  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND league_id=''1'' AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nColumn ''league_id'' in where clause is ambiguous', '', '::1', '2012-12-25 09:12:59', '2012-12-25 09:12:59'),
(60, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:18:20', '2012-12-25 10:18:20'),
(61, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:18:21', '2012-12-25 10:18:21'),
(62, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:23:11', '2012-12-25 10:23:11'),
(63, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:23:12', '2012-12-25 10:23:12'),
(64, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:33:09', '2012-12-25 10:33:09'),
(65, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:33:10', '2012-12-25 10:33:10'),
(66, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:33:49', '2012-12-25 10:33:49'),
(67, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:33:50', '2012-12-25 10:33:50'),
(68, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:34:26', '2012-12-25 10:34:26'),
(69, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:34:27', '2012-12-25 10:34:27'),
(70, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:37:25', '2012-12-25 10:37:25'),
(71, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:37:26', '2012-12-25 10:37:26'),
(72, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_actor WHERE 1 AND type=''0'' AND ma.status=1]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:37:35', '2012-12-25 10:37:35'),
(73, 'Error', 'DB Error #1054', '[SELECT actor_id,actor_name,contact_info,type  FROM ost_actor WHERE 1 AND type=''0'' AND ma.status=1 ORDER BY updated DESC,actor_name ASC LIMIT 0,30]\n\nUnknown column ''ma.status'' in ''where clause''', '', '::1', '2012-12-25 10:37:36', '2012-12-25 10:37:36'),
(74, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=ac.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=9]\n\nUnknown column ''ac.actor_id'' in ''on clause''', '', '::1', '2012-12-25 12:52:47', '2012-12-25 12:52:47'),
(75, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=ac.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=9]\n\nUnknown column ''ac.actor_id'' in ''on clause''', '', '::1', '2012-12-25 12:52:52', '2012-12-25 12:52:52'),
(76, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=ac.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=9]\n\nUnknown column ''ac.actor_id'' in ''on clause''', '', '::1', '2012-12-25 12:53:11', '2012-12-25 12:53:11'),
(77, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id=''40'' ORDER BY updated DESC, league_name ASC]\n\nUnknown column ''league_name'' in ''order clause''', '', '::1', '2012-12-26 14:43:49', '2012-12-26 14:43:49'),
(78, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id=''40'' ORDER BY updated DESC, league_name ASC]\n\nUnknown column ''league_name'' in ''order clause''', '', '::1', '2012-12-26 14:44:17', '2012-12-26 14:44:17'),
(79, 'Warning', 'Failed login attempt (staff)', 'Username: user2@gmail.com\nIP: ::1\nTIME: Dec 26, 2012, 7:49 am UTC\n\nAttempts #2', '', '::1', '2012-12-26 14:49:05', '2012-12-26 14:49:05'),
(80, 'Warning', 'Failed login attempt (staff)', 'Username: pgmkos\nIP: 10.19.10.88\nTIME: Dec 26, 2012, 8:26 am UTC\n\nAttempts #2', '', '10.19.10.88', '2012-12-26 15:26:13', '2012-12-26 15:26:13'),
(81, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 08:46:06', '2013-01-02 08:46:06'),
(82, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 08:46:06', '2013-01-02 08:46:06'),
(83, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 08:46:06', '2013-01-02 08:46:06'),
(84, 'Error', 'DB Error #1064', '[INSERT INTO ost_attachment  SET attachment_name=''bg.gif''ticket_id=140,updated=NOW()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ticket_id=140,updated=NOW()'' at line 1', '', '::1', '2013-01-02 10:47:23', '2013-01-02 10:47:23'),
(85, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:41:54', '2013-01-02 15:41:54'),
(86, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:46:48', '2013-01-02 15:46:48'),
(87, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:47:56', '2013-01-02 15:47:56'),
(88, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:49:18', '2013-01-02 15:49:18'),
(89, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:50:17', '2013-01-02 15:50:17'),
(90, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:50:31', '2013-01-02 15:50:31'),
(91, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 15:50:58', '2013-01-02 15:50:58'),
(92, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:00:15', '2013-01-02 16:00:15'),
(93, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:06:12', '2013-01-02 16:06:12'),
(94, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:06:31', '2013-01-02 16:06:31'),
(95, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:07:31', '2013-01-02 16:07:31'),
(96, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:08:11', '2013-01-02 16:08:11'),
(97, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:24:18', '2013-01-02 16:24:18'),
(98, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:27:06', '2013-01-02 16:27:06'),
(99, 'Error', 'DB Error #1064', '[DELETE FROM ost_attachment WHERE attachment_name in ()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '')'' at line 1', '', '::1', '2013-01-02 16:27:33', '2013-01-02 16:27:33'),
(100, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:06:38', '2013-01-02 17:06:38'),
(101, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:07:32', '2013-01-02 17:07:32'),
(102, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:07:32', '2013-01-02 17:07:32'),
(103, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:07:32', '2013-01-02 17:07:32'),
(104, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:30:10', '2013-01-02 17:30:10'),
(105, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:31:57', '2013-01-02 17:31:57'),
(106, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-02 17:34:30', '2013-01-02 17:34:30'),
(107, 'Error', 'DB Error #1054', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name ,count(attach.attach_id) as attachments, IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_attachment attach ON ticket.ticket_id=attach.ticket_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND status=''open'' AND isanswered=0   GROUP BY ticket.ticket_id ORDER BY priority_urgency,effective_date DESC ,ticket.created DESC LIMIT 0,25]\n\nUnknown column ''attach.attach_id'' in ''field list''', '', '::1', '2013-01-03 12:52:38', '2013-01-03 12:52:38'),
(108, 'Error', 'DB Error #1054', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name ,count(attach.attach_id) as attachments, IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_attachment attach ON ticket.ticket_id=attach.ticket_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND status=''open'' AND isanswered=0   GROUP BY ticket.ticket_id ORDER BY priority_urgency,effective_date DESC ,ticket.created DESC LIMIT 0,25]\n\nUnknown column ''attach.attach_id'' in ''field list''', '', '::1', '2013-01-03 12:53:00', '2013-01-03 12:53:00'),
(109, 'Warning', 'Failed login attempt (staff)', 'Username: kos\nIP: 10.19.10.88\nTIME: Jan 3, 2013, 6:05 am UTC\n\nAttempts #2', '', '10.19.10.88', '2013-01-03 13:05:24', '2013-01-03 13:05:24'),
(110, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_attachment  WHERE status=1]\n\nUnknown column ''status'' in ''where clause''', '', '::1', '2013-01-03 15:06:10', '2013-01-03 15:06:10'),
(111, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_attachment  WHERE status=1]\n\nUnknown column ''status'' in ''where clause''', '', '::1', '2013-01-03 15:07:19', '2013-01-03 15:07:19'),
(112, 'Error', 'DB Error #1054', '[SELECT team_id,team_name,sport_type   FROM ost_team  WHERE status=1 ORDER BY updated DESC,league_name ASC LIMIT 0,30]\n\nUnknown column ''league_name'' in ''order clause''', '', '::1', '2013-01-03 16:02:11', '2013-01-03 16:02:11'),
(113, 'Error', 'DB Error #1054', '[SELECT team_id,team_name,sport_type   FROM ost_team  WHERE status=1 ORDER BY updated DESC,league_name ASC LIMIT 0,30]\n\nUnknown column ''league_name'' in ''order clause''', '', '::1', '2013-01-03 16:02:38', '2013-01-03 16:02:38'),
(114, 'Error', 'DB Error #1064', '[INSERT INTO ost_team  SET team_name=''VietNam''sport_type=4ibc_team_id=123123,updated=NOW()]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''sport_type=4ibc_team_id=123123,updated=NOW()'' at line 1', '', '::1', '2013-01-03 16:28:50', '2013-01-03 16:28:50'),
(115, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_team AS `team`   AND ma.status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''AND ma.status=1'' at line 1', '', '::1', '2013-01-03 16:38:07', '2013-01-03 16:38:07'),
(116, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_team AS `team`   AND team.status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''AND team.status=1'' at line 1', '', '::1', '2013-01-03 16:39:12', '2013-01-03 16:39:12'),
(117, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_team AS `team`   AND team.status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''AND team.status=1'' at line 1', '', '::1', '2013-01-03 16:40:00', '2013-01-03 16:40:00'),
(118, 'Error', 'DB Error #1064', '[SELECT count(*)  FROM ost_team AS `team`   AND team.status=1]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''AND team.status=1'' at line 1', '', '::1', '2013-01-03 16:41:33', '2013-01-03 16:41:33'),
(119, 'Error', 'DB Error #1064', '[SELECT league_id FROM ost_league WHERE league_name=''league1''AND sport_type=1AND ibc_league_id=123]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ibc_league_id=123'' at line 1', '', '::1', '2013-01-03 17:02:39', '2013-01-03 17:02:39'),
(120, 'Error', 'DB Error #1064', '[SELECT league_id FROM ost_league WHERE league_name=''League''AND sport_type=3AND ibc_league_id=12123]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ibc_league_id=12123'' at line 1', '', '::1', '2013-01-03 17:03:32', '2013-01-03 17:03:32'),
(121, 'Error', 'DB Error #1064', '[SELECT league_id FROM ost_league WHERE league_name=''League''AND sport_type=3AND ibc_league_id=12123]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ibc_league_id=12123'' at line 1', '', '::1', '2013-01-03 17:07:46', '2013-01-03 17:07:46'),
(122, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_league AS `lea`  WHERE 1 AND team.status=1]\n\nUnknown column ''team.status'' in ''where clause''', '', '::1', '2013-01-03 17:10:13', '2013-01-03 17:10:13'),
(123, 'Error', 'DB Error #1054', '[SELECT count(*)  FROM ost_league AS `lea`  WHERE 1 AND team.status=1]\n\nUnknown column ''team.status'' in ''where clause''', '', '::1', '2013-01-03 17:11:37', '2013-01-03 17:11:37'),
(124, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-03 17:31:02', '2013-01-03 17:31:02'),
(125, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-03 17:32:25', '2013-01-03 17:32:25'),
(126, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 08:52:35', '2013-01-04 08:52:35'),
(127, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 08:52:57', '2013-01-04 08:52:57'),
(128, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-04 08:58:19', '2013-01-04 08:58:19'),
(129, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-04 11:00:10', '2013-01-04 11:00:10'),
(130, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-04 11:01:49', '2013-01-04 11:01:49'),
(131, 'Error', 'DB Error #1054', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC,ma.match_name ASC LIMIT 0,30]\n\nUnknown column ''ma.match_name'' in ''order clause''', '', '::1', '2013-01-04 11:05:43', '2013-01-04 11:05:43'),
(132, 'Error', 'DB Error #1052', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code,CASE sport_type\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT sport_name FROM ost_sport AS `spo` WHERE `ma`.`sport_type`=`spo`.`sport_type`\r\n                )\r\n                END AS `sport_name`,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`away_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `away_team`  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC LIMIT 0,30]\n\nColumn ''sport_type'' in field list is ambiguous', '', '::1', '2013-01-04 11:13:30', '2013-01-04 11:13:30');
INSERT INTO `ost_syslog` (`log_id`, `log_type`, `title`, `log`, `logger`, `ip_address`, `created`, `updated`) VALUES
(133, 'Error', 'DB Error #1052', '[SELECT match_id,ma.status,ma.league_id,league_name,home_id,away_id,ibc_match_id,event_date,match_code,CASE sport_type\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT sport_name FROM ost_sport AS `spo` WHERE `ma`.`sport_type`=`spo`.`sport_type`\r\n                )\r\n                END AS `sport_name`,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                )\r\n                END AS `away_team`  FROM ost_match AS ma JOIN ost_league AS lea ON ma.league_id=lea.league_id WHERE 1 AND ma.status=1 ORDER BY ma.updated DESC LIMIT 0,30]\n\nColumn ''sport_type'' in field list is ambiguous', '', '::1', '2013-01-04 11:13:55', '2013-01-04 11:13:55'),
(134, 'Error', 'DB Error #1054', '[SELECT team_id,team_name FROM ost_team WHERE status=1 AND league_id=''50'' ORDER BY updated DESC, team_name ASC]\n\nUnknown column ''league_id'' in ''where clause''', '', '::1', '2013-01-04 11:30:02', '2013-01-04 11:30:02'),
(135, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:34:47', '2013-01-04 12:34:47'),
(136, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:35:09', '2013-01-04 12:35:09'),
(137, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:41:04', '2013-01-04 12:41:04'),
(138, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:47:07', '2013-01-04 12:47:07'),
(139, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id=''48'' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:48:02', '2013-01-04 12:48:02'),
(140, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:49:57', '2013-01-04 12:49:57'),
(141, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:50:34', '2013-01-04 12:50:34'),
(142, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 12:52:35', '2013-01-04 12:52:35'),
(143, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:01:13', '2013-01-04 13:01:13'),
(144, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:02:03', '2013-01-04 13:02:03'),
(145, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:05:17', '2013-01-04 13:05:17'),
(146, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:06:10', '2013-01-04 13:06:10'),
(147, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:08:36', '2013-01-04 13:08:36'),
(148, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:13:19', '2013-01-04 13:13:19'),
(149, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:20:00', '2013-01-04 13:20:00'),
(150, 'Error', 'DB Error #1054', '[SELECT sport_type,sport_name FROM ost_sport WHERE status=1 and league_id=''5'']\n\nUnknown column ''league_id'' in ''where clause''', '', '::1', '2013-01-04 13:20:45', '2013-01-04 13:20:45'),
(151, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:22:14', '2013-01-04 13:22:14'),
(152, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:24:36', '2013-01-04 13:24:36'),
(153, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:32:14', '2013-01-04 13:32:14'),
(154, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:33:51', '2013-01-04 13:33:51'),
(155, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:38:55', '2013-01-04 13:38:55'),
(156, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:40:40', '2013-01-04 13:40:40'),
(157, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:42:45', '2013-01-04 13:42:45'),
(158, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:44:26', '2013-01-04 13:44:26'),
(159, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 13:47:01', '2013-01-04 13:47:01'),
(160, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:08:17', '2013-01-04 14:08:17'),
(161, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:21:24', '2013-01-04 14:21:24'),
(162, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:47:37', '2013-01-04 14:47:37'),
(163, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:49:29', '2013-01-04 14:49:29'),
(164, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:52:20', '2013-01-04 14:52:20'),
(165, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 14:55:01', '2013-01-04 14:55:01'),
(166, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:27:22', '2013-01-04 15:27:22'),
(167, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:32:20', '2013-01-04 15:32:20'),
(168, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:33:07', '2013-01-04 15:33:07'),
(169, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:39:41', '2013-01-04 15:39:41'),
(170, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:42:55', '2013-01-04 15:42:55'),
(171, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:48:47', '2013-01-04 15:48:47'),
(172, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:50:51', '2013-01-04 15:50:51'),
(173, 'Error', 'DB Error #1054', '[SELECT match_id ,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                    WHEN 0\r\n                    THEN('''')                \r\n                    ELSE(\r\n                    SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                    )\r\n                    END AS `away_team`FROM ost_match AS `ma` WHERE status=1 and league_id=''50'' AND sport_type=''4'' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''order clause''', '', '::1', '2013-01-04 15:51:21', '2013-01-04 15:51:21'),
(174, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:53:31', '2013-01-04 15:53:31'),
(175, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:55:31', '2013-01-04 15:55:31'),
(176, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 15:56:05', '2013-01-04 15:56:05'),
(177, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:01:27', '2013-01-04 16:01:27'),
(178, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:03:17', '2013-01-04 16:03:17'),
(179, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:21:46', '2013-01-04 16:21:46'),
(180, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:24:37', '2013-01-04 16:24:37'),
(181, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:27:18', '2013-01-04 16:27:18'),
(182, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:39:37', '2013-01-04 16:39:37'),
(183, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:44:40', '2013-01-04 16:44:40'),
(184, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:45:20', '2013-01-04 16:45:20'),
(185, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1 and league_id='''' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 16:49:43', '2013-01-04 16:49:43'),
(186, 'Error', 'DB Error #1064', '[SELECT match_id,,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                    WHEN 0\r\n                    THEN('''')                \r\n                    ELSE(\r\n                    SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                    )\r\n                    END AS `away_team` FROM ost_match AS `ma` WHERE status=1 and match_id=''6'']\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n'' at line 1', '', '::1', '2013-01-04 16:50:25', '2013-01-04 16:50:25'),
(187, 'Error', 'DB Error #1054', '[SELECT match_id ,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                    WHEN 0\r\n                    THEN('''')                \r\n                    ELSE(\r\n                    SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                    )\r\n                    END AS `away_team`FROM ost_match AS `ma` WHERE status=1 and league_id=''50'' AND sport_type=''4'' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''order clause''', '', '::1', '2013-01-04 16:58:58', '2013-01-04 16:58:58'),
(188, 'Error', 'DB Error #1054', '[SELECT match_id ,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                    WHEN 0\r\n                    THEN('''')                \r\n                    ELSE(\r\n                    SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                    )\r\n                    END AS `away_team`FROM ost_match AS `ma` WHERE status=1 and league_id=''50'' AND sport_type=''4'' ORDER BY updated DESC, match_name ASC]\n\nUnknown column ''match_name'' in ''order clause''', '', '::1', '2013-01-04 17:01:57', '2013-01-04 17:01:57'),
(189, 'Error', 'DB Error #1064', '[SELECT match_id ,CASE home_id\r\n                WHEN 0\r\n                THEN('''')                \r\n                ELSE(\r\n                SELECT team_name FROM ost_team AS `tea1` WHERE `ma`.`home_id`=`tea1`.`team_id`\r\n                )\r\n                END AS `home_team`,CASE away_id\r\n                    WHEN 0\r\n                    THEN('''')                \r\n                    ELSE(\r\n                    SELECT team_name FROM ost_team AS `tea2` WHERE `ma`.`away_id`=`tea2`.`team_id`\r\n                    )\r\n                    END AS `away_team`FROM ost_match AS `ma` WHERE status=1 and league_id=''51'' AND sport_type=''4'' ORDER BY updated DESC ASC]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''ASC'' at line 13', '', '::1', '2013-01-04 17:04:01', '2013-01-04 17:04:01'),
(190, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:06:18', '2013-01-04 17:06:18'),
(191, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 17:12:48', '2013-01-04 17:12:48'),
(192, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=169]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:15:04', '2013-01-04 17:15:04'),
(193, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=170]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:16:49', '2013-01-04 17:16:49'),
(194, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-04 17:17:54', '2013-01-04 17:17:54'),
(195, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:19:07', '2013-01-04 17:19:07'),
(196, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:21:12', '2013-01-04 17:21:12'),
(197, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:24:10', '2013-01-04 17:24:10'),
(198, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:26:17', '2013-01-04 17:26:17'),
(199, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:26:46', '2013-01-04 17:26:46'),
(200, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name\n               ,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=168]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-04 17:27:43', '2013-01-04 17:27:43'),
(201, 'Error', 'DB Error #1052', '[SELECT count(DISTINCT ticket.ticket_id)  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`   WHERE 1 AND status=''open'' AND isanswered=0 ]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2013-01-08 09:45:41', '2013-01-08 09:45:41'),
(202, 'Error', 'DB Error #1052', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name , IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND status=''open'' AND isanswered=0   GROUP BY ticket.ticket_id ORDER BY priority_urgency,effective_date DESC ,ticket.created DESC LIMIT 0,25]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2013-01-08 09:45:42', '2013-01-08 09:45:42'),
(203, 'Error', 'DB Error #1052', '[SELECT count(DISTINCT ticket.ticket_id)  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`   WHERE 1 AND status=''open'' AND isanswered=0 ]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2013-01-08 09:46:12', '2013-01-08 09:46:12'),
(204, 'Error', 'DB Error #1052', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name , IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND status=''open'' AND isanswered=0   GROUP BY ticket.ticket_id ORDER BY priority_urgency,effective_date DESC ,ticket.created DESC LIMIT 0,25]\n\nColumn ''status'' in where clause is ambiguous', '', '::1', '2013-01-08 09:46:13', '2013-01-08 09:46:13'),
(205, 'Error', 'DB Error #1064', '[SELECT count(DISTINCT ticket.ticket_id)  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`   WHERE 1 AND `ma`.`event_date`>=FROM_UNIXTIME(1357516800) AND `ma`.`event_date`<=FROM_UNIXTIME(1357516800 24:00)]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''24:00)'' at line 1', '', '::1', '2013-01-08 10:08:00', '2013-01-08 10:08:00'),
(206, 'Error', 'DB Error #1064', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name , IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND `ma`.`event_date`>=FROM_UNIXTIME(1357516800) AND `ma`.`event_date`<=FROM_UNIXTIME(1357516800 24:00)  GROUP BY ticket.ticket_id ORDER BY ticket.created DESC LIMIT 0,15]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''24:00)  GROUP BY ticket.ticket_id ORDER BY ticket.created DESC LIMIT 0,15'' at line 7', '', '::1', '2013-01-08 10:08:01', '2013-01-08 10:08:01'),
(207, 'Error', 'DB Error #1064', '[SELECT count(DISTINCT ticket.ticket_id)  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`   WHERE 1 AND `ma`.`event_date`>=FROM_UNIXTIME(1357516800) AND `ma`.`event_date`<=FROM_UNIXTIME(1357516800 24:00)]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''24:00)'' at line 1', '', '::1', '2013-01-08 10:08:45', '2013-01-08 10:08:45'),
(208, 'Error', 'DB Error #1064', '[SELECT DISTINCT ticket.ticket_id,lock_id,ticketID,ticket.dept_id,ticket.staff_id,subject,league,`match`,trader,suspender,csd,name,email,dept_name ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.* ,count(attach.attachment_id) as attachments, CASE ticket.staff_id\r\n                WHEN 0\r\n                THEN ('''')\r\n                ELSE (\r\n                SELECT CONCAT(firstname,'' '',lastname) AS staff_name FROM ost_staff AS staff WHERE staff.staff_id=ticket.staff_id\r\n                )\r\n               END AS staff_name , IF(ticket.reopened is NULL,ticket.created,ticket.reopened) as effective_date  FROM ost_ticket ticket  LEFT JOIN ost_department `dept` ON `ticket`.`dept_id`=`dept`.`dept_id`  LEFT JOIN ost_match `ma` ON ticket.match=`ma`.`match_id`  LEFT JOIN ost_attachment `attach` ON `ticket`.`ticket_id`=`attach`.`ticket_id`  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()   WHERE 1 AND `ma`.`event_date`>=FROM_UNIXTIME(1357516800) AND `ma`.`event_date`<=FROM_UNIXTIME(1357516800 24:00)  GROUP BY ticket.ticket_id ORDER BY ticket.created DESC LIMIT 0,15]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''24:00)  GROUP BY ticket.ticket_id ORDER BY ticket.created DESC LIMIT 0,15'' at line 7', '', '::1', '2013-01-08 10:08:46', '2013-01-08 10:08:46'),
(209, 'Error', 'DB Error #1064', '[SELECT count(*) FROM ost_kb_premade premade LEFT JOIN ost_department USING(dept_id)   WHERE MATCH(title,answer) AGAINST (''%''sample''%'')]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''sample''%'')'' at line 1', '', '::1', '2013-01-08 10:37:43', '2013-01-08 10:37:43'),
(210, 'Error', 'DB Error #1064', '[SELECT premade.*,dept_name  FROM ost_kb_premade premade LEFT JOIN ost_department USING(dept_id)   WHERE MATCH(title,answer) AGAINST (''%''sample''%'')  LIMIT 0,25]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ''sample''%'')  LIMIT 0,25'' at line 1', '', '::1', '2013-01-08 10:37:44', '2013-01-08 10:37:44'),
(211, 'Warning', 'Failed login attempt (staff)', 'Username: admin\nIP: 10.19.10.88\nTIME: Jan 9, 2013, 3:41 am UTC\n\nAttempts #2', '', '10.19.10.88', '2013-01-09 10:41:11', '2013-01-09 10:41:11'),
(212, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:55:49', '2013-01-09 16:55:49'),
(213, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:55:51', '2013-01-09 16:55:51'),
(214, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=174]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:16', '2013-01-09 16:57:16'),
(215, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-09 16:57:17', '2013-01-09 16:57:17'),
(216, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:18', '2013-01-09 16:57:18'),
(217, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=174]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:30', '2013-01-09 16:57:30'),
(218, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-09 16:57:31', '2013-01-09 16:57:31'),
(219, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:32', '2013-01-09 16:57:32'),
(220, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=174]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:39', '2013-01-09 16:57:39'),
(221, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-09 16:57:40', '2013-01-09 16:57:40'),
(222, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:57:41', '2013-01-09 16:57:41'),
(223, 'Error', 'DB Error #1054', '[ SELECT  ticket.*,topic.topic_id as topicId,lock_id,dept_name,lea.league_name,ma.match_name,tra.actor_name AS trader_name,su.actor_name AS suspender_name,cs.actor_name AS csd_name,priority_desc FROM ost_ticket ticket  LEFT JOIN ost_league lea ON ticket.league=lea.league_id  LEFT JOIN ost_match ma ON ticket.match=ma.match_id  LEFT JOIN ost_actor tra ON ticket.trader=tra.actor_id  LEFT JOIN ost_actor su ON ticket.suspender=su.actor_id  LEFT JOIN ost_actor cs ON ticket.csd=cs.actor_id  LEFT JOIN ost_department dept ON ticket.dept_id=dept.dept_id  LEFT JOIN ost_ticket_priority pri ON ticket.priority_id=pri.priority_id  LEFT JOIN ost_help_topic topic ON ticket.topic_id=topic.topic_id  LEFT JOIN ost_ticket_lock tlock ON ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW()  WHERE ticket.ticket_id=174]\n\nUnknown column ''ma.match_name'' in ''field list''', '', '::1', '2013-01-09 16:58:01', '2013-01-09 16:58:01'),
(224, 'Error', 'DB Error #1064', '[DELETE FROM ost_ticket WHERE ticket_id=]\n\nYou have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '''' at line 1', '', '::1', '2013-01-09 16:58:02', '2013-01-09 16:58:02'),
(225, 'Error', 'DB Error #1054', '[SELECT match_id,match_name FROM ost_match WHERE status=1]\n\nUnknown column ''match_name'' in ''field list''', '', '::1', '2013-01-09 16:58:03', '2013-01-09 16:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `ost_team`
--

CREATE TABLE IF NOT EXISTS `ost_team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `ibc_team_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`team_id`),
  KEY `sport_type` (`sport_type`,`ibc_team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ost_team`
--

INSERT INTO `ost_team` (`team_id`, `team_name`, `sport_type`, `ibc_team_id`, `status`, `updated`) VALUES
(1, 'VietNam', 4, 123123, 1, '2013-01-03 16:29:49'),
(2, 'VietNam', 1, 123123, 1, '2013-01-03 16:32:36'),
(3, 'Laos', 4, 12312323, 1, '2013-01-03 16:56:43'),
(4, 'Thailand', 4, 654, 1, '2013-01-09 11:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket`
--

CREATE TABLE IF NOT EXISTS `ost_ticket` (
  `ticket_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` int(11) unsigned NOT NULL DEFAULT '0',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '1',
  `priority_id` int(10) unsigned NOT NULL DEFAULT '2',
  `topic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(120) DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `subject` varchar(64) NOT NULL DEFAULT '[no subject]',
  `sport_type` int(11) NOT NULL,
  `league` int(64) NOT NULL,
  `match` int(64) NOT NULL,
  `trader` int(64) DEFAULT NULL,
  `suspender` int(64) DEFAULT NULL,
  `csd` int(64) DEFAULT NULL,
  `helptopic` varchar(255) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `phone_ext` varchar(8) DEFAULT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `source` enum('Web','Email','Phone','Other') NOT NULL DEFAULT 'Other',
  `isoverdue` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isanswered` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `duedate` datetime DEFAULT NULL,
  `reopened` datetime DEFAULT NULL,
  `closed` datetime DEFAULT NULL,
  `lastmessage` datetime DEFAULT NULL,
  `lastresponse` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `email_extid` (`ticketID`,`email`),
  KEY `dept_id` (`dept_id`),
  KEY `staff_id` (`staff_id`),
  KEY `status` (`status`),
  KEY `priority_id` (`priority_id`),
  KEY `created` (`created`),
  KEY `closed` (`closed`),
  KEY `duedate` (`duedate`),
  KEY `topic_id` (`topic_id`),
  KEY `sport_type` (`sport_type`,`league`,`match`,`trader`,`suspender`,`csd`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=175 ;

--
-- Dumping data for table `ost_ticket`
--

INSERT INTO `ost_ticket` (`ticket_id`, `ticketID`, `dept_id`, `priority_id`, `topic_id`, `staff_id`, `email`, `name`, `subject`, `sport_type`, `league`, `match`, `trader`, `suspender`, `csd`, `helptopic`, `phone`, `phone_ext`, `ip_address`, `status`, `source`, `isoverdue`, `isanswered`, `duedate`, `reopened`, `closed`, `lastmessage`, `lastresponse`, `created`, `updated`) VALUES
(171, 970083, 3, 2, 2, 2, 'user1@gmail.com', 'alice', 'test mail 123', 4, 50, 18, 8, 5, 6, 'Billing', '', '', '::1', 'open', 'Email', 0, 0, NULL, NULL, NULL, '2013-01-07 16:50:03', '2013-01-07 16:50:03', '2013-01-07 16:50:03', '2013-01-07 17:28:42'),
(172, 516113, 3, 3, 2, 2, 'alice.le@nexcel.vn', 'duong', 'test mail 123', 4, 50, 24, 8, 5, 12, 'Billing', '', '', '::1', 'open', 'Email', 0, 0, NULL, NULL, NULL, '2013-01-08 14:42:01', '2013-01-08 14:42:01', '2013-01-08 14:42:01', '2013-01-08 14:42:01'),
(170, 346994, 2, 2, 0, 0, 'user2@gmail.com', 'alice', 'test mail', 4, 50, 23, 14, 13, 6, '', '', '', '::1', 'open', 'Email', 0, 0, '2013-01-30 00:00:00', NULL, NULL, NULL, NULL, '2013-01-04 17:16:28', '2013-01-07 13:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_attachment`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_attachment` (
  `attach_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ref_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ref_type` enum('M','R') NOT NULL DEFAULT 'M',
  `file_size` varchar(32) NOT NULL DEFAULT '',
  `file_name` varchar(128) NOT NULL DEFAULT '',
  `file_key` varchar(128) NOT NULL DEFAULT '',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`attach_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `ref_type` (`ref_type`),
  KEY `ref_id` (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_lock`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_lock` (
  `lock_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `expire` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lock_id`),
  UNIQUE KEY `ticket_id` (`ticket_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=251 ;

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_message`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_message` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `messageId` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `headers` text,
  `source` varchar(16) DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`msg_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `msgId` (`messageId`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=171 ;

--
-- Dumping data for table `ost_ticket_message`
--

INSERT INTO `ost_ticket_message` (`msg_id`, `ticket_id`, `messageId`, `message`, `headers`, `source`, `ip_address`, `created`, `updated`) VALUES
(167, 171, '', 'Ticket created by staff', '', 'Email', '::1', '2013-01-07 16:50:03', NULL),
(168, 172, '', 'Ticket created by staff', '', 'Email', '::1', '2013-01-08 14:42:01', NULL),
(2, 2, '', 'Ticket created by staff', '', 'Other', '::1', '2012-12-19 14:44:08', NULL),
(3, 3, '', 'Ticket created by staff', '', 'Other', '::1', '2012-12-19 15:01:17', NULL),
(4, 4, '', 'Ticket created by staff', '', 'Phone', '::1', '2012-12-19 15:06:20', NULL),
(5, 6, '', 'Ticket created by staff', '', 'Phone', '::1', '2012-12-20 16:24:44', NULL),
(7, 8, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-20 17:34:10', NULL),
(61, 62, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:05:48', NULL),
(62, 63, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:06:09', NULL),
(71, 72, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:12:25', NULL),
(73, 74, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:12:48', NULL),
(74, 75, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:13:06', NULL),
(76, 77, '', 'Ticket created by staff', '', 'Email', '::1', '2012-12-28 15:14:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_note`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_note` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(10) unsigned NOT NULL DEFAULT '0',
  `source` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT 'Generic Intermal Notes',
  `note` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`note_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `staff_id` (`staff_id`),
  FULLTEXT KEY `note` (`note`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=191 ;

--
-- Dumping data for table `ost_ticket_note`
--

INSERT INTO `ost_ticket_note` (`note_id`, `ticket_id`, `staff_id`, `source`, `title`, `note`, `created`) VALUES
(1, 2, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', 'Ticket assigned', '2012-12-19 14:44:09'),
(2, 2, 1, 'Admin Admin', 'Ticket Updated', '222222222', '2012-12-19 14:53:40'),
(3, 2, 1, 'Admin Admin', 'Ticket Updated', '66666', '2012-12-19 14:54:39'),
(4, 2, 1, 'system', 'Ticket Reopened', 'Ticket reopened by Admin Admin', '2012-12-19 14:56:17'),
(5, 2, 1, 'system', 'Ticket Marked Overdue', 'Ticket flagged as overdue by Admin Admin', '2012-12-19 14:57:42'),
(6, 2, 1, 'system', 'Ticket Closed', 'Ticket closed without response by Admin Admin', '2012-12-19 14:58:35'),
(7, 3, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', 'Ticket assigned', '2012-12-19 15:01:18'),
(8, 4, 1, 'system', 'New Ticket by Staff', 'Ticket created by staff -Admin Admin', '2012-12-19 15:06:20'),
(9, 4, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', '87978', '2012-12-19 15:07:14'),
(10, 4, 1, 'Admin Admin', 'Ticket Assigned to User2 user2', 'edrert', '2012-12-19 15:08:44'),
(11, 4, 1, 'Admin Admin', '444', 'sw', '2012-12-20 09:56:15'),
(12, 6, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', 'Ticket assigned', '2012-12-20 16:24:45'),
(14, 6, 1, 'Admin Admin', 'Ticket Updated', 'werwer', '2012-12-20 17:18:14'),
(15, 8, 2, 'User1 user1', 'Ticket Assigned to User2 user2', 'Ticket assigned', '2012-12-20 17:34:11'),
(16, 4, 1, 'system', 'Ticket Marked Overdue', 'Ticket flagged as overdue by the system.', '2012-12-24 08:33:09'),
(182, 170, 1, 'Admin Admin', 'Ticket Updated', 'q1w2eqw', '2013-01-07 13:34:26'),
(183, 170, 1, 'Admin Admin', 'Ticket Updated', '123', '2013-01-07 13:41:28'),
(184, 171, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', 'Ticket assigned', '2013-01-07 16:50:04'),
(185, 171, 1, 'Admin Admin', 'Ticket Updated', 'sdf', '2013-01-07 17:28:42'),
(186, 172, 1, 'Admin Admin', 'Ticket Assigned to User1 user1', 'Ticket assigned', '2013-01-08 14:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_priority`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_priority` (
  `priority_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `priority` varchar(60) NOT NULL DEFAULT '',
  `priority_desc` varchar(30) NOT NULL DEFAULT '',
  `priority_color` varchar(7) NOT NULL DEFAULT '',
  `priority_urgency` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ispublic` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`priority_id`),
  UNIQUE KEY `priority` (`priority`),
  KEY `priority_urgency` (`priority_urgency`),
  KEY `ispublic` (`ispublic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ost_ticket_priority`
--

INSERT INTO `ost_ticket_priority` (`priority_id`, `priority`, `priority_desc`, `priority_color`, `priority_urgency`, `ispublic`) VALUES
(1, 'low', 'Low', '#DDFFDD', 4, 1),
(2, 'normal', 'Normal', '#FFFFF0', 3, 1),
(3, 'high', 'High', '#FEE7E7', 2, 1),
(4, 'emergency', 'Emergency', '#FEE7E7', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket_response`
--

CREATE TABLE IF NOT EXISTS `ost_ticket_response` (
  `response_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `ticket_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_id` int(11) unsigned NOT NULL DEFAULT '0',
  `staff_name` varchar(32) NOT NULL DEFAULT '',
  `response` text NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`response_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `msg_id` (`msg_id`),
  KEY `staff_id` (`staff_id`),
  FULLTEXT KEY `response` (`response`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=165 ;

--
-- Dumping data for table `ost_ticket_response`
--

INSERT INTO `ost_ticket_response` (`response_id`, `msg_id`, `ticket_id`, `staff_id`, `staff_name`, `response`, `ip_address`, `created`, `updated`) VALUES
(1, 2, 2, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use\r\n\r\n\r\nDuong,\r\n\r\nYour ticket #374876 created on Wed, Dec 19 2012 2:44pm is in R&D Team department.\r\n\r\n\r\nosTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.', '::1', '2012-12-19 14:44:08', '0000-00-00 00:00:00'),
(2, 3, 3, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.', '::1', '2012-12-19 15:01:17', '0000-00-00 00:00:00'),
(3, 4, 4, 1, 'Admin Admin', 'Duong222,\r\n\r\nYour ticket #727814 created on Wed, Dec 19 2012 3:06pm is in R&D Team department.', '::1', '2012-12-19 15:06:20', '0000-00-00 00:00:00'),
(4, 4, 4, 1, 'Admin Admin', 'rtyt', '::1', '2012-12-20 09:55:29', '0000-00-00 00:00:00'),
(5, 4, 4, 1, 'Admin Admin', '4665', '::1', '2012-12-20 09:56:01', '0000-00-00 00:00:00'),
(6, 4, 4, 1, 'Admin Admin', 'ytyt', '::1', '2012-12-20 10:05:50', '0000-00-00 00:00:00'),
(7, 5, 6, 1, 'Admin Admin', 'duong 3,\r\n\r\nYour ticket #437557 created on Thu, Dec 20 2012 4:24pm is in R&D Team department.', '::1', '2012-12-20 16:24:44', '0000-00-00 00:00:00'),
(9, 7, 8, 2, 'User1 user1', 'Duong4,\r\n\r\nYour ticket #694168 created on Thu, Dec 20 2012 5:34pm is in R&D Team department.', '::1', '2012-12-20 17:34:10', '0000-00-00 00:00:00'),
(161, 167, 171, 1, 'Admin Admin', 'alice,\r\n\r\nYour ticket #970083 created on Mon, Jan 7 2013 4:50pm is in R&D Team department.', '::1', '2013-01-07 16:50:03', '0000-00-00 00:00:00'),
(162, 168, 172, 1, 'Admin Admin', 'duong,\r\n\r\nYour ticket #516113 created on Tue, Jan 8 2013 2:42pm is in R&D Team department.', '::1', '2013-01-08 14:42:01', '0000-00-00 00:00:00'),
(158, 0, 0, 1, 'Admin Admin', ',\r\n\r\nYour ticket # created on  is in  department.', '::1', '2013-01-04 17:06:18', '0000-00-00 00:00:00'),
(159, 0, 0, 1, 'Admin Admin', ',\r\n\r\nYour ticket # created on  is in  department.', '::1', '2013-01-04 17:15:04', '0000-00-00 00:00:00'),
(160, 0, 0, 1, 'Admin Admin', ',\r\n\r\nYour ticket # created on  is in  department.', '::1', '2013-01-04 17:16:49', '0000-00-00 00:00:00'),
(61, 61, 62, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #264425 created on Fri, Dec 28 2012 3:05pm is in R&D Team department.', '::1', '2012-12-28 15:05:48', '0000-00-00 00:00:00'),
(62, 61, 62, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #264425 created on Fri, Dec 28 2012 3:05pm is in R&D Team department.', '::1', '2012-12-28 15:05:48', '0000-00-00 00:00:00'),
(63, 62, 63, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #230540 created on Fri, Dec 28 2012 3:06pm is in R&D Team department.', '::1', '2012-12-28 15:06:09', '0000-00-00 00:00:00'),
(70, 71, 72, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #760151 created on Fri, Dec 28 2012 3:12pm is in R&D Team department.', '::1', '2012-12-28 15:12:25', '0000-00-00 00:00:00'),
(72, 73, 74, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #189591 created on Fri, Dec 28 2012 3:12pm is in R&D Team department.', '::1', '2012-12-28 15:12:48', '0000-00-00 00:00:00'),
(73, 74, 75, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #536131 created on Fri, Dec 28 2012 3:13pm is in R&D Team department.', '::1', '2012-12-28 15:13:06', '0000-00-00 00:00:00'),
(75, 76, 77, 1, 'Admin Admin', 'osTicket is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.\r\n\r\n\r\nalice,\r\n\r\nYour ticket #607269 created on Fri, Dec 28 2012 3:14pm is in R&D Team department.', '::1', '2012-12-28 15:14:43', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ost_timezone`
--

CREATE TABLE IF NOT EXISTS `ost_timezone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `offset` float(3,1) NOT NULL DEFAULT '0.0',
  `timezone` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `ost_timezone`
--

INSERT INTO `ost_timezone` (`id`, `offset`, `timezone`) VALUES
(1, -12.0, 'Eniwetok, Kwajalein'),
(2, -11.0, 'Midway Island, Samoa'),
(3, -10.0, 'Hawaii'),
(4, -9.0, 'Alaska'),
(5, -8.0, 'Pacific Time (US & Canada)'),
(6, -7.0, 'Mountain Time (US & Canada)'),
(7, -6.0, 'Central Time (US & Canada), Mexico City'),
(8, -5.0, 'Eastern Time (US & Canada), Bogota, Lima'),
(9, -4.0, 'Atlantic Time (Canada), Caracas, La Paz'),
(10, -3.5, 'Newfoundland'),
(11, -3.0, 'Brazil, Buenos Aires, Georgetown'),
(12, -2.0, 'Mid-Atlantic'),
(13, -1.0, 'Azores, Cape Verde Islands'),
(14, 0.0, 'Western Europe Time, London, Lisbon, Casablanca'),
(15, 1.0, 'Brussels, Copenhagen, Madrid, Paris'),
(16, 2.0, 'Kaliningrad, South Africa'),
(17, 3.0, 'Baghdad, Riyadh, Moscow, St. Petersburg'),
(18, 3.5, 'Tehran'),
(19, 4.0, 'Abu Dhabi, Muscat, Baku, Tbilisi'),
(20, 4.5, 'Kabul'),
(21, 5.0, 'Ekaterinburg, Islamabad, Karachi, Tashkent'),
(22, 5.5, 'Bombay, Calcutta, Madras, New Delhi'),
(23, 6.0, 'Almaty, Dhaka, Colombo'),
(24, 7.0, 'Bangkok, Hanoi, Jakarta'),
(25, 8.0, 'Beijing, Perth, Singapore, Hong Kong'),
(26, 9.0, 'Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
(27, 9.5, 'Adelaide, Darwin'),
(28, 10.0, 'Eastern Australia, Guam, Vladivostok'),
(29, 11.0, 'Magadan, Solomon Islands, New Caledonia'),
(30, 12.0, 'Auckland, Wellington, Fiji, Kamchatka');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
