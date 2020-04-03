<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Name: Third Helper
 * Author: Scrisoft
 * Created: 01/07/2017
 * Here you will find the following functions:
 * manage_db - manages the database
 * */
if (!function_exists('manage_db')) {
    function manage_db() {
        $CI = get_instance();
        // Load Posts Model
        $CI->load->model('posts');
        // Load Posts SQL Queries
        $tables = $CI->db->list_fields('activity');
        if (!in_array('dlt', $tables)) {
            $CI->db->query('ALTER TABLE activity ADD dlt VARCHAR(250)');
            $CI->db->query('ALTER TABLE activity ADD autocomment TEXT');
        }
        $tables = $CI->db->list_fields('posts');
        if (!in_array('dlt', $tables)) {
            $CI->db->query('ALTER TABLE posts ADD parent BIGINT(20)');
            $CI->db->query('ALTER TABLE posts ADD dlt VARCHAR(250)');
        }
        $tables = $CI->db->list_fields('posts');
        if (!in_array('fb_boost_id', $tables)) {
            $CI->db->query('ALTER TABLE posts ADD fb_boost_id BIGINT(20) AFTER view');
        }
        if (!in_array('resend', $tables)) {
            $CI->db->query('ALTER TABLE posts ADD resend BIGINT(20) AFTER sent_time');
            $CI->db->query('ALTER TABLE posts MODIFY COLUMN body VARBINARY(4000)');
        }
        // Load Campaigns Model
        $CI->load->model('campaigns');
        // Load Campaigns SQL Queries
        $tables = $CI->db->table_exists('campaigns');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `campaigns` (`campaign_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` bigint(20) NOT NULL,`type` varchar(20) NOT NULL,`name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`created` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        // Load Dictionary Model
        $CI->load->model('dictionary');
        // Load Dictionary SQL Queries
        $tables = $CI->db->table_exists('dictionary');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `dictionary` (`dict_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` bigint(20) NOT NULL,`name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
            $CI->db->query('ALTER TABLE `users_meta` MODIFY COLUMN meta_value TEXT');
        }
        // Load Campaigns Meta
        $tables = $CI->db->table_exists('campaigns_meta');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `campaigns_meta` (`meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint(20) NOT NULL, `meta_name` varchar(30) NOT NULL, `meta_val1` varchar(250) NOT NULL, `meta_val2` varchar(250) NOT NULL, `meta_val3` varchar(250) NOT NULL, `meta_val4` varchar(250) NOT NULL, `meta_val5` varchar(250) NOT NULL, `meta_val6` varchar(250) NOT NULL, `meta_val7` varchar(250) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }    
        // Load Lists Model
        $CI->load->model('lists');
        // Load Lists SQL Queries
        $tables = $CI->db->table_exists('lists');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `lists` (`list_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` bigint(20) NOT NULL,`type` varchar(20) NOT NULL,`name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`created` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->table_exists('lists_meta');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `lists_meta` (`meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`list_id` bigint(20) NOT NULL,`user_id` int(11) NOT NULL,`body` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`active` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        // Load Media Model
        $CI->load->model('media');        
        $tables = $CI->db->table_exists('media');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `media` (`media_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `user_id` int(11) NOT NULL, `body` text NOT NULL, `type` varchar(10) NOT NULL, `created` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->list_fields('media');
        if (!in_array('cover', $tables)) {
            $CI->db->query('ALTER TABLE media ADD cover TEXT AFTER body');
            $CI->db->query('ALTER TABLE media ADD size VARCHAR(20) AFTER cover');
        }        
        $tables = $CI->db->list_fields('lists_meta');
        if (!in_array('ip', $tables)) {
            $CI->db->query('ALTER TABLE lists_meta ADD city VARCHAR(250) AFTER body');
            $CI->db->query('ALTER TABLE lists_meta ADD country VARCHAR(250) AFTER body');
            $CI->db->query('ALTER TABLE lists_meta ADD ip VARCHAR(250) AFTER body');
        }
        // Load Plans Model
        $CI->load->model('plans');
        // Load Plans SQL Queries
        $tables = $CI->db->list_fields('payments');
        if (!in_array('source', $tables)) {
            $CI->db->query('ALTER TABLE payments ADD source VARCHAR(20)');
            $CI->db->query('ALTER TABLE payments MODIFY COLUMN txn_id VARCHAR(250)');
        }
        if (!in_array('recurring', $tables)) {
            $CI->db->query('ALTER TABLE payments ADD recurring tinyint(1)');
        }
        $tables = $CI->db->list_fields('plans');
        if (!in_array('sent_emails', $tables)) {
            $CI->db->query('ALTER TABLE plans ADD sent_emails VARCHAR(20) AFTER publish_posts');
        }
        $tables = $CI->db->list_fields('plans');
        if (!in_array('storage', $tables)) {
            $CI->db->query('ALTER TABLE plans ADD storage VARCHAR(250) AFTER sent_emails');
        }
        // Load Resend Model
        $CI->load->model('resend');
        // Load Resend SQL Queries
        $tables = $CI->db->table_exists('resend');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `resend` (`resend_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` int(11) NOT NULL, `time` varchar(30) NOT NULL, `created` varchar(30) NOT NULL, `updated` varchar(30) NOT NULL, `active` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->table_exists('resend_meta');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `resend_meta` (`meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`resend_id` bigint(20) NOT NULL, `rule1` varchar(30) NOT NULL, `rule2` varchar(30) NOT NULL, `rule3` varchar(30) NOT NULL, `rule4` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->table_exists('resend_rules');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `resend_rules` (`rule_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `resend_id` bigint(20) NOT NULL, `meta_id` bigint(20) NOT NULL, `status` tinyint(1) NOT NULL, `totime` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        // Load Scheduled Model
        $CI->load->model('scheduled');
        // Load Scheduled SQL Queries
        $tables = $CI->db->table_exists('scheduled');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `scheduled` (`scheduled_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` int(11) NOT NULL,`type` varchar(20) NOT NULL,`campaign_id` bigint(20) NOT NULL,`list_id` bigint(20) NOT NULL,`template_id` bigint(20) NOT NULL,`con` tinyint(1) NOT NULL,`template` bigint(20) NOT NULL,`send_at` varchar(30) NOT NULL,`a` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->list_fields('scheduled');
        if (!in_array('resend', $tables)) {
            $CI->db->query('ALTER TABLE `scheduled` ADD resend BIGINT(20) AFTER send_at');
            $CI->db->query('ALTER TABLE `posts` MODIFY COLUMN `title` VARCHAR(250)');
            $CI->db->query('ALTER TABLE `posts` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
        }
        $tables = $CI->db->table_exists('scheduled_stats');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `scheduled_stats` (`stat_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`sched_id` bigint(20) NOT NULL,`campaign_id` bigint(20) NOT NULL,`list_id` bigint(20) NOT NULL,`template_id` bigint(20) NOT NULL,`body` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`unsubscribed` tinyint(1) NOT NULL,`readed` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        // Load Templates Model
        $CI->load->model('templates');
        // Load Templates SQL Queries
        $tables = $CI->db->table_exists('templates');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `templates` (`template_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` int(11) NOT NULL,`campaign_id` bigint(20) NOT NULL,`type` varchar(20) NOT NULL,`title` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,`created` varchar(30) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        $tables = $CI->db->list_fields('templates');
        if (!in_array('resend', $tables)) {
            $CI->db->query('ALTER TABLE templates ADD resend BIGINT(20)');
            $CI->db->query('ALTER TABLE templates ADD list_id BIGINT(20)');
        }
        // Load Notifications Model
        $CI->load->model('notifications');
        // Load Notifications SQL Queries
        $tables = $CI->db->table_exists('notifications');
        if ($tables) {
            $query = $CI->db->select('*')->from('notifications')->where(['notification_id' => 1000])->get();
            if($query->num_rows() < 1) {
                $CI->db->query("INSERT INTO `notifications` (`notification_id`, `notification_title`, `notification_name`, `notification_body`, `sent_time`, `template`, `template_name`) VALUES
(1000, 'The Planned Post was completed', 'Post Completation Notification', '<p>Dear [username]</p><p>Your planned post, [post] was published the planned number of times and will not be more published.</p>', '', 1, 'planned-completed-confirmation')");
            }
            $query = $CI->db->select('*')->from('notifications')->where(['notification_id' => 1100])->get();
            if($query->num_rows() < 1) {
                $CI->db->query("INSERT INTO `notifications` (`notification_id`, `notification_title`, `notification_name`, `notification_body`, `sent_time`, `template`, `template_name`) VALUES
(1100, 'New Ticket Reply', 'New Ticket Reply', '<p>Dear [username]</p><p>You have a new reply for your opened ticket. </p>', '', 1, 'ticket-notification-reply')");
            }
            $query = $CI->db->select('*')->from('notifications')->where(['notification_id' => 2000])->get();
            if($query->num_rows() < 1) {
                $CI->db->query("INSERT INTO `notifications` (`notification_id`, `notification_title`, `notification_name`, `notification_body`, `sent_time`, `template`, `template_name`) VALUES
(2000, 'The Planned Email Template was completed', 'Email Template Completation Notification', '<p>Dear [username]</p><p>Your planned email template, [template] was sent the planned number of times and will not be more sent.</p>', '', 1, 'planned-email-completed-confirmation')");
            }
        }
        // Load Networks Model
        $CI->load->model('networks');
        // Load Networks SQL Queries
        $tables = $CI->db->list_fields('networks');
        if (!in_array('api_key', $tables)) {
            $CI->db->query('ALTER TABLE networks ADD api_key VARCHAR(250)');
            $CI->db->query('ALTER TABLE networks ADD api_secret VARCHAR(250)');
        }
        // Load Team Model
        $CI->load->model('team');
        // Load Team SQL Queries
        $tables = $CI->db->table_exists('teams');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `teams` (`member_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,`user_id` int(11) NOT NULL,`member_username` varchar(30) NOT NULL,`member_password` varchar(250) NOT NULL,`date_joined` datetime NOT NULL,`last_access` datetime NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
            $CI->db->query('ALTER TABLE plans ADD teams tinyint(1) AFTER features');
        }
        $tables = $CI->db->list_fields('teams');
        if ( !in_array('member_email', $tables) ) {
            $CI->db->query('ALTER TABLE teams ADD member_email varchar(250) AFTER member_password');
            $CI->db->query('ALTER TABLE teams ADD status tinyint(1) AFTER member_email');
            $CI->db->query('ALTER TABLE teams ADD about_member TEXT AFTER status');
        }        
        $tables = $CI->db->list_fields('campaigns_meta');
        if (!in_array('meta_val8', $tables)) {
            $CI->db->query('ALTER TABLE campaigns_meta ADD meta_val8 VARCHAR(250) AFTER meta_val7');
            $CI->db->query('ALTER TABLE campaigns_meta ADD meta_val9 VARCHAR(250) AFTER meta_val8');
            $CI->db->query('ALTER TABLE campaigns_meta ADD meta_val10 VARCHAR(250) AFTER meta_val9');
            $CI->db->query('ALTER TABLE `networks` MODIFY COLUMN token TEXT');
            $CI->db->query('ALTER TABLE `networks` MODIFY COLUMN secret TEXT');
        }
        $tables = $CI->db->list_fields('campaigns_meta');
        if (!in_array('meta_val11', $tables)) {
            $CI->db->query('ALTER TABLE campaigns_meta ADD meta_val11 VARCHAR(250) AFTER meta_val10');
            $CI->db->query('ALTER TABLE posts_meta ADD network_status VARCHAR(250) AFTER status');
            $CI->db->query('ALTER TABLE rss ADD include TEXT AFTER publish_url');
            $CI->db->query('ALTER TABLE rss ADD exclude TEXT AFTER include');
        }
        $posts_meta = $CI->db->list_fields('posts_meta');
        if (!in_array('published_id', $posts_meta)) {
            $CI->db->query('ALTER TABLE `posts_meta` ADD published_id TEXT AFTER network_status');
        }   
        
        // Load Coupons Model
        $CI->load->model('codes');
        // Load Coupons SQL Queries
        $tables = $CI->db->table_exists('coupons');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `coupons` (`coupon_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `code` varchar(20) NOT NULL, `value` varchar(3) NOT NULL, `type` tinyint(1) NOT NULL, `count` bigint(20) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }
        // Load Invoice Model
        $CI->load->model('invoice');
        // Load Coupons SQL Queries
        $tables = $CI->db->table_exists('invoices');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `invoices` (`invoice_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `transaction_id` varchar(250) NOT NULL, `plan_id` int(6) NOT NULL, `invoice_date` datetime NOT NULL, `user_id` bigint(20) NOT NULL, `invoice_title` text NOT NULL, `invoice_text` text NOT NULL, `amount` varchar(250) NOT NULL, `currency` varchar(20) NOT NULL, `taxes` varchar(20) NOT NULL, `total` varchar(20) NOT NULL, `from_period` datetime NOT NULL, `to_period` datetime NOT NULL, `gateway` varchar(250) NOT NULL, `status` tinyint(1) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        }  
        $tables = $CI->db->list_fields('users');
        if (!in_array('first_name', $tables)) {
            $CI->db->query('ALTER TABLE users ADD first_name varchar(30) AFTER email');
            $CI->db->query('ALTER TABLE users ADD last_name varchar(30) AFTER email');
            $CI->db->query('ALTER TABLE `options` MODIFY COLUMN option_value TEXT');
            $CI->db->query('ALTER TABLE `options` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
        }
        // Load Activities Model
        $CI->load->model('activities');
        // Load Activities SQL Queries
        $tables = $CI->db->table_exists('activities');
        if (!$tables) {
            $CI->db->query('CREATE TABLE `activities` (`activity_id` bigint(20) AUTO_INCREMENT PRIMARY KEY, `app` varchar(250) COLLATE utf8_unicode_ci NOT NULL, `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL, `id` bigint(20) NOT NULL, `user_id` bigint(20) NOT NULL, `member_id` bigint(20) NOT NULL, `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }  
        // Load Campaigns SQL Queries
        $tables = $CI->db->table_exists('plans_meta');
        if ( !$tables ) {
            $CI->db->query('CREATE TABLE IF NOT EXISTS `plans_meta` (
              `meta_id` int(6) NOT NULL AUTO_INCREMENT,
              `plan_id` int(6) NOT NULL,
              `meta_name` TEXT COLLATE utf8_unicode_ci NOT NULL,
              `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
              PRIMARY KEY (`meta_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        // Load Campaigns SQL Queries
        $tables = $CI->db->table_exists('rss_accounts');
        if ( !$tables ) {
            $CI->db->query('CREATE TABLE `rss_accounts` (
              `account_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `network_id` bigint(20) NOT NULL,
              `rss_id` bigint(20) NOT NULL,
              PRIMARY KEY (`account_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        }
        // Load Campaigns SQL Queries
        $tables = $CI->db->table_exists('rss_posts_meta');
        if ( !$tables ) {
            $CI->db->query('CREATE TABLE `rss_posts_meta` (
              `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
              `post_id` bigint(20) NOT NULL,
              `network_id` bigint(20) NOT NULL,
              `network_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
              `sent_time` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
              `status` tinyint(1) NOT NULL,
              `network_status` text,
              `published_id` text,
              PRIMARY KEY (`meta_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
            
        }
        // Load Rss SQL Queries
        $tables = $CI->db->list_fields('rss');
        if (!in_array('refferal', $tables)) {
            $CI->db->query('ALTER TABLE rss ADD refferal VARCHAR(250)');
            $CI->db->query('ALTER TABLE rss_posts MODIFY COLUMN published VARCHAR(30)');
        }
        // Load Rss SQL Queries
        $tables = $CI->db->list_fields('rss');
        if (!in_array('remove_url', $tables)) {
            $CI->db->query('ALTER TABLE rss ADD COLUMN remove_url tinyint(1) AFTER publish_url');
            $CI->db->query('ALTER TABLE rss ADD COLUMN keep_html tinyint(1) AFTER remove_url');
        }
        if (!in_array('period', $tables)) {
            $CI->db->query('ALTER TABLE rss ADD COLUMN period VARCHAR(10)');
            $CI->db->query('ALTER TABLE rss ADD COLUMN updated VARCHAR(30)');
            $CI->db->query('ALTER TABLE rss ADD COLUMN type tinyint(1)');
            $CI->db->query('ALTER TABLE `rss_posts` ADD COLUMN img TEXT AFTER url');
        }
        $tables28 = $CI->db->list_fields('rss_posts');
        if (!in_array('network_status', $tables28)) {
            $CI->db->query('ALTER TABLE `rss_posts` ADD network_status TEXT AFTER scheduled');
            $CI->db->query('ALTER TABLE `posts_meta` MODIFY COLUMN network_status TEXT');
        }
        $tables29 = $CI->db->list_fields('rss');
        if (!in_array('group_id', $tables29)) {
            $CI->db->query('ALTER TABLE `rss` ADD group_id tinyint(1) AFTER publish_url');
            $CI->db->query('ALTER TABLE rss ADD rss_description VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER rss_name');
            $CI->db->query('ALTER TABLE rss_posts ADD status tinyint(1) AFTER user_id');
        }
        
    }
    
}