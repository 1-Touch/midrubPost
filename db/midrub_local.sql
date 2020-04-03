-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2020 at 01:31 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `midrub_local`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` bigint(20) NOT NULL,
  `app` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `app`, `template`, `id`, `user_id`, `member_id`, `created`) VALUES
(1, 'posts', 'posts', 1, 119, 0, '1585547179'),
(2, 'posts', 'posts', 2, 119, 0, '1585550264'),
(3, 'posts', 'posts', 3, 119, 0, '1585553397'),
(4, 'posts', 'comments', 70, 119, 0, '1585554126'),
(5, 'posts', 'comments', 70, 119, 0, '1585554172'),
(6, 'posts', 'posts', 4, 119, 0, '1585561646'),
(7, 'posts', 'posts', 5, 119, 0, '1585561670'),
(8, 'posts', 'posts', 6, 119, 0, '1585561865'),
(9, 'posts', 'posts', 7, 119, 0, '1585561967'),
(10, 'posts', 'posts', 8, 119, 0, '1585561997'),
(11, 'posts', 'posts', 9, 119, 0, '1585562013'),
(12, 'posts', 'posts', 10, 119, 0, '1585562044'),
(13, 'posts', 'posts', 11, 119, 0, '1585562268'),
(14, 'posts', 'posts', 12, 119, 0, '1585562288'),
(15, 'posts', 'posts', 13, 119, 0, '1585562847'),
(16, 'posts', 'comments', 70, 119, 0, '1585562963'),
(17, 'posts', 'posts', 14, 119, 0, '1585646224'),
(18, 'posts', 'comments', 70, 119, 0, '1585654592'),
(19, 'posts', 'comments', 70, 119, 0, '1585655926'),
(20, 'posts', 'posts', 15, 119, 0, '1585715510'),
(21, 'posts', 'posts', 16, 119, 0, '1585716615'),
(22, 'posts', 'posts', 1, 119, 0, '1585725943'),
(23, 'posts', 'comments', 1, 119, 0, '1585726065'),
(24, 'posts', 'posts', 2, 119, 0, '1585743090'),
(25, 'posts', 'posts', 3, 119, 0, '1585743109'),
(26, 'posts', 'posts', 4, 119, 0, '1585801893'),
(27, 'posts', 'posts', 5, 119, 0, '1585898509');

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `followed` tinyint(1) NOT NULL,
  `view` tinyint(1) NOT NULL,
  `dlt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autocomment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activity_id`, `user_id`, `net_id`, `body`, `network_name`, `network_id`, `created`, `followed`, `view`, `dlt`, `autocomment`) VALUES
(27, 119, '147733319916755_150295749660512', '14', 'facebook_groups', 158, '1585646228', 0, 0, NULL, NULL),
(28, 119, '147733319916755_150722076284546', '16', 'facebook_groups', 158, '1585716618', 0, 0, NULL, NULL),
(29, 119, '147733319916755_150858492937571', '3', 'facebook_groups', 2, '1585743111', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_meta`
--

CREATE TABLE `activity_meta` (
  `meta_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `author_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `author_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `view` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrator_dashboard_widgets`
--

CREATE TABLE `administrator_dashboard_widgets` (
  `widget_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `widget_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `order` smallint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_account`
--

CREATE TABLE `ads_account` (
  `ads_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts`
--

CREATE TABLE `ads_boosts` (
  `boost_id` bigint(20) NOT NULL,
  `boost_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts_meta`
--

CREATE TABLE `ads_boosts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `boost_id` bigint(20) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts_stats`
--

CREATE TABLE `ads_boosts_stats` (
  `stat_id` bigint(20) NOT NULL,
  `boost_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `publisher_platforms` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `platform_status` text COLLATE utf8_unicode_ci NOT NULL,
  `ad_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ad_id` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_status` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels`
--

CREATE TABLE `ads_labels` (
  `label_id` bigint(20) NOT NULL,
  `label_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels_meta`
--

CREATE TABLE `ads_labels_meta` (
  `meta_id` bigint(20) NOT NULL,
  `label_id` bigint(20) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels_stats`
--

CREATE TABLE `ads_labels_stats` (
  `stat_id` bigint(20) NOT NULL,
  `label_id` bigint(20) NOT NULL,
  `publisher_platforms` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `platform_status` text COLLATE utf8_unicode_ci NOT NULL,
  `ad_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ad_id` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_status` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_networks`
--

CREATE TABLE `ads_networks` (
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `expires` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `secret` text COLLATE utf8_unicode_ci NOT NULL,
  `extra` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bots`
--

CREATE TABLE `bots` (
  `bot_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rule1` text COLLATE utf8_unicode_ci NOT NULL,
  `rule2` text COLLATE utf8_unicode_ci NOT NULL,
  `rule3` text COLLATE utf8_unicode_ci NOT NULL,
  `rule4` text COLLATE utf8_unicode_ci NOT NULL,
  `rule5` text COLLATE utf8_unicode_ci NOT NULL,
  `rule6` text COLLATE utf8_unicode_ci NOT NULL,
  `rule7` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_meta`
--

CREATE TABLE `campaigns_meta` (
  `meta_id` bigint(20) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `meta_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val1` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val2` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val3` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val4` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val5` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val6` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val7` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val8` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val9` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val10` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val11` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_replies`
--

CREATE TABLE `chatbot_replies` (
  `reply_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `accuracy` int(3) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_replies_categories`
--

CREATE TABLE `chatbot_replies_categories` (
  `id` bigint(20) NOT NULL,
  `reply_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_replies_response`
--

CREATE TABLE `chatbot_replies_response` (
  `response_id` bigint(20) NOT NULL,
  `reply_id` bigint(20) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classifications`
--

CREATE TABLE `classifications` (
  `classification_id` bigint(20) NOT NULL,
  `slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `parent` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `classifications`
--

INSERT INTO `classifications` (`classification_id`, `slug`, `type`, `parent`) VALUES
(8, 'user_left_menu', 'menu', 0),
(9, 'user_left_menu', 'menu', 0),
(10, 'user_left_menu', 'menu', 0),
(11, 'user_left_menu', 'menu', 0),
(12, 'user_left_menu', 'menu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `classifications_meta`
--

CREATE TABLE `classifications_meta` (
  `meta_id` bigint(20) NOT NULL,
  `classification_id` bigint(20) NOT NULL,
  `meta_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_extra` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `classifications_meta`
--

INSERT INTO `classifications_meta` (`meta_id`, `classification_id`, `meta_slug`, `meta_name`, `meta_value`, `meta_extra`, `language`) VALUES
(36, 8, 'name', 'name', 'Dashboard', '', 'english'),
(37, 8, 'selected_component', 'selected_component', 'dashboard', 'app', 'english'),
(38, 8, 'permalink', 'permalink', '', '', 'english'),
(39, 8, 'description', 'description', '', '', 'english'),
(40, 8, 'class', 'class', 'icon-speedometer', '', 'english'),
(41, 9, 'name', 'name', 'Posts', '', 'english'),
(42, 9, 'selected_component', 'selected_component', 'posts', 'app', 'english'),
(43, 9, 'permalink', 'permalink', '', '', 'english'),
(44, 9, 'description', 'description', '', '', 'english'),
(45, 9, 'class', 'class', 'icon-layers', '', 'english'),
(46, 10, 'name', 'name', 'Storage', '', 'english'),
(47, 10, 'selected_component', 'selected_component', 'storage', 'app', 'english'),
(48, 10, 'permalink', 'permalink', '', '', 'english'),
(49, 10, 'description', 'description', '', '', 'english'),
(50, 10, 'class', 'class', 'icon-drawer', '', 'english'),
(51, 11, 'name', 'name', 'Settings', '', 'english'),
(52, 11, 'selected_component', 'selected_component', 'settings', 'component', 'english'),
(53, 11, 'permalink', 'permalink', '', '', 'english'),
(54, 11, 'description', 'description', '', '', 'english'),
(55, 11, 'class', 'class', 'icon-settings', '', 'english'),
(56, 12, 'name', 'name', 'Chatbot', '', 'english'),
(57, 12, 'selected_component', 'selected_component', 'chatbot', 'app', 'english'),
(58, 12, 'permalink', 'permalink', '', '', 'english'),
(59, 12, 'description', 'description', '', '', 'english'),
(60, 12, 'class', 'class', 'icon-bubbles', '', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) NOT NULL,
  `comment` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `content_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `contents_category` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_component` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_theme` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents_classifications`
--

CREATE TABLE `contents_classifications` (
  `classification_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `classification_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `classification_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents_meta`
--

CREATE TABLE `contents_meta` (
  `meta_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `meta_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_extra` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` bigint(20) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dictionary`
--

CREATE TABLE `dictionary` (
  `dict_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles`
--

CREATE TABLE `faq_articles` (
  `article_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles_categories`
--

CREATE TABLE `faq_articles_categories` (
  `meta_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `category_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles_meta`
--

CREATE TABLE `faq_articles_meta` (
  `meta_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `category_id` int(6) NOT NULL,
  `parent` int(6) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories_meta`
--

CREATE TABLE `faq_categories_meta` (
  `meta_id` bigint(20) NOT NULL,
  `category_id` int(6) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `guide_id` bigint(20) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `cover` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` bigint(20) NOT NULL,
  `transaction_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `plan_id` int(6) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `invoice_title` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_text` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `taxes` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `from_period` datetime NOT NULL,
  `to_period` datetime NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE `lists` (
  `list_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lists_meta`
--

CREATE TABLE `lists_meta` (
  `meta_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `cover` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`media_id`, `user_id`, `body`, `cover`, `size`, `type`, `created`) VALUES
(2, 119, 'http://localhost/midrub_local/assets/share/5e7c7720d35cb-1585215264.jpeg', 'http://localhost/midrub_local/assets/share/5e7c7720d35cb-1585215264.jpeg', '15182', 'image', '1585215265'),
(3, 119, 'http://localhost/midrub_local/assets/share/5e7d8d454e9b6-1585286469.mp4', 'http://localhost/midrub_local/assets/share/5e7d8d454e9b6-1585286469-cover.png', '1053651', 'video', '1585286469');

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

CREATE TABLE `networks` (
  `network_id` int(3) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_avatar` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `expires` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `token` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `completed` tinyint(1) NOT NULL,
  `api_key` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_secret` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `networks`
--

INSERT INTO `networks` (`network_id`, `network_name`, `net_id`, `user_id`, `user_name`, `user_avatar`, `date`, `expires`, `token`, `secret`, `completed`, `api_key`, `api_secret`) VALUES
(1, 'facebook_pages', '276014732508044', 119, 'Love is the end of life', '', '2020-04-01 12:54:37', '', 'EAAI8UvDF3yIBAG4xpNaCcd5GDJ44IT3ic32SR8d9ZA7xvFkZA0ZCQ5eBfq0qNN7rZCI3oZASH3k9ZCv58RHKpAZBA8kmYGPA86mxUCXQL5IZCSZC08Fo2TdZAfZBgiHoOLHynIaYqz8rA3FuvqnQovsOD1j3ndGpMswcuAgnmm1zW92CQZDZD', 'EAAI8UvDF3yIBAPwewBwAkmbqLZCaRZBZB4ZARJg9p9H4XyfYO2neQ7hzTdDVlea4eLmqGqULdhwWxObMTbJDDkdlg6SZBD0ZCFtSGoCZC4BuCFLdM02D0Xz7QACORcrVZCV8weiRMR6fu6Wn6GvB8KJIvFHy0ZALrkW5JRgHW6YbZBrwZDZD', 0, NULL, NULL),
(2, 'facebook_groups', '147733319916755', 119, 'ABP Group', '', '2020-04-01 03:27:46', '', 'EAAI8UvDF3yIBADVuUu79d7HO8fOJrZCSrw4VLHYW93zVX1xu7DC5IhSQ77JirhLaob45BOSRpxZAhllWTZCWaPCPPF5ZCKsSJQlo56JznBpqf5tnaz5GXGFnYdZBK3CrLrZBC3IU47jWZCghr8lnOqZBL3m0IXh0omqVWMjWB4CVJwZDZD', ' ', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` bigint(20) NOT NULL,
  `notification_title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `notification_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `notification_body` text COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `template` tinyint(1) NOT NULL,
  `template_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notification_title`, `notification_name`, `notification_body`, `sent_time`, `template`, `template_name`) VALUES
(1, 'Welcome to [site_name]', 'Welcome message(without confirmation)', '<p>You can login here: [login_address]</p><p>Using this username and password:</p><p>Username: [username]</p><p>Password: *** Password you set during signup ***</p><p>Cheers!</p><p>The [site_name] Team</p><p><br></p>', '', 1, 'welcome-message-no-confirmation'),
(2, 'Welcome to [site_name]', 'Welcome message(with confirmation)', '<p>To activate your account and verify your email address, </p><p>please click the following link: [confirmation_link]</p><p><br></p>', '', 1, 'welcome-message-with-confirmation'),
(3, 'Your account has been activated', 'Success confirmation message', '<p>Congratulations, your account has been activated!</p><p>You can login here: [login_address]</p><p>Using this username and password:</p><p>Username: [username]</p><p>Password: *** Password you set during signup ***</p><p>Cheers!</p><p>The [site_name] Team</p>', '', 1, 'success-confirmation-message'),
(4, 'Password Reset', 'Reset password message', '<p>Dear [username]</p><p>To reset the password to your [site_name]\'s account, click the link below: </p><p>[reset_link]<br></p>', '', 1, 'password-reset'),
(5, 'Your password has been reset successfully', 'Success password changed message', '<p>Congratulations, your account has been activated!</p><p>You can login here: [login_address]<br></p><p><br></p>', '', 1, 'success-password-changed'),
(6, 'Your message wasn\'t published successfully', 'Publishing message error', '<p>You messagge wasn\'t published successfully on a social network.</p><p>You can login here: [login_address]<br></p>', '', 1, 'error-sent-notification'),
(7, 'Resend Confirmation Email', 'Resend confirmation email', '<p>To activate your account and verify your email address,</p><p>please click the following link: [confirmation_link]</p>', '', 1, 'resend-confirmation-email'),
(8, 'Your new account was created successfully', 'Send password to new users', '<p>A new account has been created for you on [site_name].</p><p>You can login here <span xss=\"removed\">[login_address]</span></p><p><span xss=\"removed\">Username: [username]</span></p><p><span xss=\"removed\">Password: [password]</span></p>', '', 1, 'send-password-new-users'),
(9, 'Scheduled Notification', 'Scheduled notification', '<p>An user has scheduled a new message.</p><p>Please Sign In: <span xss=\"removed\">[login_address]</span></p><p><br></p>', '', 1, 'scheduled-notification'),
(12, 'New user registration', 'New user registration', 'A new user has registered at <span xss=\"removed\">[site_name]</span>', '', 1, 'new-user-notification'),
(1000, 'The Planned Post was completed', 'Post Completation Notification', '<p>Dear [username]</p><p>Your planned post, [post] was published the planned number of times and will not be more published.</p>', '', 1, 'planned-completed-confirmation'),
(1100, 'New Ticket Reply', 'New Ticket Reply', '<p>Dear [username]</p><p>You have a new reply for your opened ticket.</p>', '', 1, 'ticket-notification-reply'),
(2000, 'The Planned Email Template was completed', 'Email Template Completation Notification', '<p>Dear [username]</p><p>Your planned email template, [template] was sent the planned number of times and will not be more sent.</p>', '', 1, 'planned-email-completed-confirmation');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_stats`
--

CREATE TABLE `notifications_stats` (
  `stat_id` bigint(20) NOT NULL,
  `notification_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_applications`
--

CREATE TABLE `oauth_applications` (
  `application_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_url` text COLLATE utf8_unicode_ci NOT NULL,
  `cancel_url` text COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_applications_permissions`
--

CREATE TABLE `oauth_applications_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes`
--

CREATE TABLE `oauth_authorization_codes` (
  `code_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `code` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes_permissions`
--

CREATE TABLE `oauth_authorization_codes_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `code_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_permissions`
--

CREATE TABLE `oauth_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens`
--

CREATE TABLE `oauth_tokens` (
  `token_id` bigint(20) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens_permissions`
--

CREATE TABLE `oauth_tokens_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `token_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` bigint(20) NOT NULL,
  `option_key` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES
(2, 'app_dashboard_enable', '1'),
(3, 'app_dashboard_enable_default_widgets', '1'),
(4, 'app_dashboard_left_side_position', '1'),
(5, 'app_posts_enable', '1'),
(6, 'app_posts_enable_composer', '1'),
(7, 'app_posts_enable_scheduled', '1'),
(8, 'app_posts_enable_insights', '1'),
(9, 'app_posts_enable_history', '1'),
(10, 'app_posts_rss_feeds', '1'),
(11, 'app_posts_enable_faq', '1'),
(12, 'app_posts_enable_url_download', '1'),
(13, 'app_storage_enable', '1'),
(14, 'app_storage_enable_url_download', '1'),
(15, 'themes_activated_user_theme', 'blue'),
(16, 'facebook_ad_labels', '1'),
(17, 'component_faq_enable', '1'),
(18, 'component_notifications_enable', '1'),
(19, 'component_plans_enable', '1'),
(20, 'component_settings_enable', '1'),
(21, 'component_team_enable', '1'),
(22, 'app_facebook_ads_enable', '1'),
(23, 'app_facebook_ads_enable_posts_boosting', '1'),
(27, 'facebook_pages_app_id', '629276877905698'),
(28, 'facebook_pages_app_secret', '259071e67e3e7c97ee5e9b1624401f68'),
(29, 'facebook_pages', '1'),
(30, 'facebook_live_app_id', '629276877905698'),
(31, 'facebook_live_app_secret', '259071e67e3e7c97ee5e9b1624401f68'),
(32, 'facebook_live', '1'),
(33, 'facebook_instant_articles_app_id', '629276877905698'),
(34, 'facebook_instant_articles_app_secret', '259071e67e3e7c97ee5e9b1624401f68'),
(35, 'facebook_instant_articles', '1'),
(58, 'facebook_groups_app_id', '629276877905698'),
(59, 'facebook_groups_app_secret', '259071e67e3e7c97ee5e9b1624401f68'),
(60, 'facebook_group_only_administrator', '1'),
(61, 'facebook_groups', '1'),
(62, 'app_chatbot_enable', '1'),
(63, 'app_chatbot_enable_activity', '1'),
(64, 'app_chatbot_enable_history', '1');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `txn_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `plan_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_planifications`
--

CREATE TABLE `planner_planifications` (
  `planification_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_planifications_networks`
--

CREATE TABLE `planner_planifications_networks` (
  `id` bigint(20) NOT NULL,
  `planification_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_planifications_posts`
--

CREATE TABLE `planner_planifications_posts` (
  `id` bigint(20) NOT NULL,
  `planification_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_planifications_rules`
--

CREATE TABLE `planner_planifications_rules` (
  `rule_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `planification_id` bigint(20) NOT NULL,
  `date_from` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_to` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `time_from` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time_to` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mon` tinyint(1) NOT NULL,
  `tue` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thu` tinyint(1) NOT NULL,
  `fri` tinyint(1) NOT NULL,
  `sat` tinyint(1) NOT NULL,
  `sun` tinyint(1) NOT NULL,
  `plan_order` tinyint(1) NOT NULL,
  `plan_limit` tinyint(1) NOT NULL,
  `plan_interval` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_planifications_rules_meta`
--

CREATE TABLE `planner_planifications_rules_meta` (
  `meta_id` bigint(20) NOT NULL,
  `planification_id` bigint(20) NOT NULL,
  `rule_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `exact_date` datetime NOT NULL,
  `scheduled` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_id` int(6) NOT NULL,
  `plan_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `plan_price` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `currency_sign` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `network_accounts` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sent_emails` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `storage` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `features` text COLLATE utf8_unicode_ci NOT NULL,
  `teams` tinyint(1) DEFAULT NULL,
  `header` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` bigint(10) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `popular` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `trial` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_price`, `currency_sign`, `currency_code`, `network_accounts`, `sent_emails`, `storage`, `features`, `teams`, `header`, `period`, `visible`, `popular`, `featured`, `trial`) VALUES
(1, 'Free Plan', '4.00', '$', 'USD', '30', '10', '60000000', '1 Social Profiles\n1 Feed Rss\nReal-time Analytics\nMessage Scheduling\n', 5, 'for personal use', 30, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plans_meta`
--

CREATE TABLE `plans_meta` (
  `meta_id` int(6) NOT NULL,
  `plan_id` int(6) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `plans_meta`
--

INSERT INTO `plans_meta` (`meta_id`, `plan_id`, `meta_name`, `meta_value`) VALUES
(1, 1, 'publish_posts', '3000'),
(2, 1, 'rss_feeds', '3000'),
(3, 1, 'facebook_ad_labels', '1'),
(4, 1, 'app_dashboard', '1'),
(5, 1, 'app_posts', '1'),
(6, 1, 'app_storage', '1'),
(7, 1, 'facebook_groups', '1'),
(8, 1, 'facebook_instant_articles', '1'),
(9, 1, 'facebook_live', '1'),
(10, 1, 'facebook_pages', '1'),
(11, 1, 'app_facebook_ads', '1'),
(12, 1, 'app_facebook_chatbot_replies', ''),
(13, 1, 'app_facebook_commenter_replies', ''),
(14, 1, 'planner_allowed_plannifications', ''),
(15, 1, 'stream_tabs_limit', ''),
(16, 1, 'app_chatbot', '0'),
(17, 1, 'app_commenter', '0'),
(18, 1, 'app_planner', '0'),
(19, 1, 'app_stream', '0');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varbinary(4000) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `ip_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `view` tinyint(1) NOT NULL,
  `fb_boost_id` bigint(20) DEFAULT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `dlt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `body`, `title`, `url`, `img`, `video`, `category`, `sent_time`, `resend`, `ip_address`, `status`, `view`, `fb_boost_id`, `parent`, `dlt`) VALUES
(1, 119, 0x4869696920467269656e64730a, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585725943', NULL, '::1', 1, 1, NULL, NULL, NULL),
(2, 119, 0x6974732067726f75707070700a, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585743090', NULL, '::1', 1, 1, NULL, NULL, NULL),
(3, 119, 0x6974732067726f75707070700a, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585743109', NULL, '::1', 1, 1, NULL, NULL, NULL),
(4, 119, 0x486920697427732031303a31300a, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585802400', NULL, '::1', 2, 1, NULL, NULL, NULL),
(5, 119, 0x68696969, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585898509', NULL, '::1', 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts_meta`
--

CREATE TABLE `posts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published_id` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts_meta`
--

INSERT INTO `posts_meta` (`meta_id`, `post_id`, `network_id`, `network_name`, `sent_time`, `status`, `network_status`, `published_id`) VALUES
(1, 1, 1, 'facebook_pages', '1585725945', 1, NULL, '276014732508044_2682962181813275'),
(2, 3, 2, 'facebook_groups', '1585743111', 1, NULL, '147733319916755_150858492937571'),
(3, 4, 2, 'facebook_groups', '1585801893', 0, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referrer_id` bigint(20) NOT NULL,
  `referrer` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` bigint(20) NOT NULL,
  `earned` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend`
--

CREATE TABLE `resend` (
  `resend_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `updated` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend_meta`
--

CREATE TABLE `resend_meta` (
  `meta_id` bigint(20) NOT NULL,
  `resend_id` bigint(20) NOT NULL,
  `rule1` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule3` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule4` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend_rules`
--

CREATE TABLE `resend_rules` (
  `rule_id` bigint(20) NOT NULL,
  `resend_id` bigint(20) NOT NULL,
  `meta_id` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `totime` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss`
--

CREATE TABLE `rss` (
  `rss_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rss_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `rss_description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `rss_url` text COLLATE utf8_unicode_ci NOT NULL,
  `publish_description` tinyint(1) NOT NULL,
  `publish_url` tinyint(1) NOT NULL,
  `remove_url` tinyint(1) DEFAULT NULL,
  `keep_html` tinyint(1) DEFAULT NULL,
  `group_id` bigint(20) NOT NULL,
  `include` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclude` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `added` datetime NOT NULL,
  `pub` tinyint(1) NOT NULL,
  `refferal` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_accounts`
--

CREATE TABLE `rss_accounts` (
  `account_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `rss_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_posts`
--

CREATE TABLE `rss_posts` (
  `post_id` bigint(20) NOT NULL,
  `rss_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scheduled` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_posts_meta`
--

CREATE TABLE `rss_posts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published_id` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled`
--

CREATE TABLE `scheduled` (
  `scheduled_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `con` tinyint(1) NOT NULL,
  `template` bigint(20) NOT NULL,
  `send_at` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `a` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_stats`
--

CREATE TABLE `scheduled_stats` (
  `stat_id` bigint(20) NOT NULL,
  `sched_id` bigint(20) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `body` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `unsubscribed` tinyint(1) NOT NULL,
  `readed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_update` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `member_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `member_password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `member_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `about_member` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_joined` datetime NOT NULL,
  `last_access` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams_roles`
--

CREATE TABLE `teams_roles` (
  `role_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams_roles_permission`
--

CREATE TABLE `teams_roles_permission` (
  `permission_id` bigint(20) NOT NULL,
  `role_id` int(20) NOT NULL,
  `permission` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `list_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `important` tinyint(1) DEFAULT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_meta`
--

CREATE TABLE `tickets_meta` (
  `meta_id` bigint(20) NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_fields`
--

CREATE TABLE `transactions_fields` (
  `field_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `field_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `field_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_options`
--

CREATE TABLE `transactions_options` (
  `option_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `option_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `url_id` bigint(20) NOT NULL,
  `original_url` text CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urls_stats`
--

CREATE TABLE `urls_stats` (
  `stats_id` bigint(20) NOT NULL,
  `url_id` bigint(20) NOT NULL,
  `network_name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `color` varchar(30) CHARACTER SET utf8 NOT NULL,
  `ip_address` varchar(30) CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_joined` datetime NOT NULL,
  `last_access` datetime DEFAULT NULL,
  `ip_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `reset_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activate` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `last_name`, `first_name`, `password`, `role`, `status`, `date_joined`, `last_access`, `ip_address`, `reset_code`, `activate`) VALUES
(104, 'administrator', 'admin@example.com', NULL, NULL, '$2a$08$CwRg961g2QCS1kBjA0NgAOs8Dg31QzOP6mNxF.OdrCd5BqAmtLyOe', 1, 1, '2016-08-11 10:37:16', '2020-04-01 09:15:14', '', ' ', ''),
(118, 'testuser', 'user@email.com', NULL, NULL, '$2a$08$fcmlgRj56zPvpYvAc3v9Ze8Tp4xX7cKmoSJZOhEqTIjvZmFtdfu/O', 0, 1, '2016-10-10 12:37:03', '2016-10-10 15:41:36', '', ' ', ''),
(119, 'pranav', 'pranav@1touch.market', 'Kumar', 'Pranav', '$2a$08$GIHEpvb.JwhkWlf.PN.X4OvO8J7ow99kIfgso1SerekxFp.zfnpmq', 0, 1, '2020-03-19 12:37:31', '2020-04-03 09:11:48', '::1', ' ', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_meta`
--

CREATE TABLE `users_meta` (
  `meta_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_name` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_meta`
--

INSERT INTO `users_meta` (`meta_id`, `user_id`, `meta_name`, `meta_value`) VALUES
(1, 119, 'plan', '1'),
(2, 119, 'plan_end', '2020-04-22 05:52:21'),
(3, 119, 'published_posts', 'a:2:{s:4:\"date\";s:7:\"2020-04\";s:5:\"posts\";i:4;}'),
(4, 119, 'user_storage', '1068833');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `activity_meta`
--
ALTER TABLE `activity_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `administrator_dashboard_widgets`
--
ALTER TABLE `administrator_dashboard_widgets`
  ADD PRIMARY KEY (`widget_id`);

--
-- Indexes for table `ads_account`
--
ALTER TABLE `ads_account`
  ADD PRIMARY KEY (`ads_id`);

--
-- Indexes for table `ads_boosts`
--
ALTER TABLE `ads_boosts`
  ADD PRIMARY KEY (`boost_id`);

--
-- Indexes for table `ads_boosts_meta`
--
ALTER TABLE `ads_boosts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `ads_boosts_stats`
--
ALTER TABLE `ads_boosts_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `ads_labels`
--
ALTER TABLE `ads_labels`
  ADD PRIMARY KEY (`label_id`);

--
-- Indexes for table `ads_labels_meta`
--
ALTER TABLE `ads_labels_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `ads_labels_stats`
--
ALTER TABLE `ads_labels_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `ads_networks`
--
ALTER TABLE `ads_networks`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`bot_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`);

--
-- Indexes for table `campaigns_meta`
--
ALTER TABLE `campaigns_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `chatbot_replies`
--
ALTER TABLE `chatbot_replies`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `chatbot_replies_categories`
--
ALTER TABLE `chatbot_replies_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_replies_response`
--
ALTER TABLE `chatbot_replies_response`
  ADD PRIMARY KEY (`response_id`);

--
-- Indexes for table `classifications`
--
ALTER TABLE `classifications`
  ADD PRIMARY KEY (`classification_id`);

--
-- Indexes for table `classifications_meta`
--
ALTER TABLE `classifications_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `contents_classifications`
--
ALTER TABLE `contents_classifications`
  ADD PRIMARY KEY (`classification_id`);

--
-- Indexes for table `contents_meta`
--
ALTER TABLE `contents_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `dictionary`
--
ALTER TABLE `dictionary`
  ADD PRIMARY KEY (`dict_id`);

--
-- Indexes for table `faq_articles`
--
ALTER TABLE `faq_articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `faq_articles_categories`
--
ALTER TABLE `faq_articles_categories`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `faq_articles_meta`
--
ALTER TABLE `faq_articles_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `faq_categories_meta`
--
ALTER TABLE `faq_categories_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`guide_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`list_id`);

--
-- Indexes for table `lists_meta`
--
ALTER TABLE `lists_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `networks`
--
ALTER TABLE `networks`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `notifications_stats`
--
ALTER TABLE `notifications_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `oauth_applications`
--
ALTER TABLE `oauth_applications`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `oauth_applications_permissions`
--
ALTER TABLE `oauth_applications_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  ADD PRIMARY KEY (`code_id`);

--
-- Indexes for table `oauth_authorization_codes_permissions`
--
ALTER TABLE `oauth_authorization_codes_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_permissions`
--
ALTER TABLE `oauth_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_tokens`
--
ALTER TABLE `oauth_tokens`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `oauth_tokens_permissions`
--
ALTER TABLE `oauth_tokens_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planner_planifications`
--
ALTER TABLE `planner_planifications`
  ADD PRIMARY KEY (`planification_id`);

--
-- Indexes for table `planner_planifications_networks`
--
ALTER TABLE `planner_planifications_networks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planner_planifications_posts`
--
ALTER TABLE `planner_planifications_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planner_planifications_rules`
--
ALTER TABLE `planner_planifications_rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `planner_planifications_rules_meta`
--
ALTER TABLE `planner_planifications_rules_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `plans_meta`
--
ALTER TABLE `plans_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `posts_meta`
--
ALTER TABLE `posts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`referrer_id`);

--
-- Indexes for table `resend`
--
ALTER TABLE `resend`
  ADD PRIMARY KEY (`resend_id`);

--
-- Indexes for table `resend_meta`
--
ALTER TABLE `resend_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `resend_rules`
--
ALTER TABLE `resend_rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `rss`
--
ALTER TABLE `rss`
  ADD PRIMARY KEY (`rss_id`);

--
-- Indexes for table `rss_accounts`
--
ALTER TABLE `rss_accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `rss_posts`
--
ALTER TABLE `rss_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `rss_posts_meta`
--
ALTER TABLE `rss_posts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `scheduled`
--
ALTER TABLE `scheduled`
  ADD PRIMARY KEY (`scheduled_id`);

--
-- Indexes for table `scheduled_stats`
--
ALTER TABLE `scheduled_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `teams_roles`
--
ALTER TABLE `teams_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `teams_roles_permission`
--
ALTER TABLE `teams_roles_permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tickets_meta`
--
ALTER TABLE `tickets_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transactions_fields`
--
ALTER TABLE `transactions_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `transactions_options`
--
ALTER TABLE `transactions_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`url_id`);

--
-- Indexes for table `urls_stats`
--
ALTER TABLE `urls_stats`
  ADD PRIMARY KEY (`stats_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_meta`
--
ALTER TABLE `users_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `activity_meta`
--
ALTER TABLE `activity_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `administrator_dashboard_widgets`
--
ALTER TABLE `administrator_dashboard_widgets`
  MODIFY `widget_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_account`
--
ALTER TABLE `ads_account`
  MODIFY `ads_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts`
--
ALTER TABLE `ads_boosts`
  MODIFY `boost_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts_meta`
--
ALTER TABLE `ads_boosts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts_stats`
--
ALTER TABLE `ads_boosts_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels`
--
ALTER TABLE `ads_labels`
  MODIFY `label_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels_meta`
--
ALTER TABLE `ads_labels_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels_stats`
--
ALTER TABLE `ads_labels_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_networks`
--
ALTER TABLE `ads_networks`
  MODIFY `network_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bots`
--
ALTER TABLE `bots`
  MODIFY `bot_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns_meta`
--
ALTER TABLE `campaigns_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_replies`
--
ALTER TABLE `chatbot_replies`
  MODIFY `reply_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_replies_categories`
--
ALTER TABLE `chatbot_replies_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbot_replies_response`
--
ALTER TABLE `chatbot_replies_response`
  MODIFY `response_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classifications`
--
ALTER TABLE `classifications`
  MODIFY `classification_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `classifications_meta`
--
ALTER TABLE `classifications_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `content_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents_classifications`
--
ALTER TABLE `contents_classifications`
  MODIFY `classification_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents_meta`
--
ALTER TABLE `contents_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dictionary`
--
ALTER TABLE `dictionary`
  MODIFY `dict_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_articles`
--
ALTER TABLE `faq_articles`
  MODIFY `article_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_articles_categories`
--
ALTER TABLE `faq_articles_categories`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_articles_meta`
--
ALTER TABLE `faq_articles_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `category_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories_meta`
--
ALTER TABLE `faq_categories_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `guide_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `list_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lists_meta`
--
ALTER TABLE `lists_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `networks`
--
ALTER TABLE `networks`
  MODIFY `network_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2014;

--
-- AUTO_INCREMENT for table `notifications_stats`
--
ALTER TABLE `notifications_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_applications`
--
ALTER TABLE `oauth_applications`
  MODIFY `application_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_applications_permissions`
--
ALTER TABLE `oauth_applications_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  MODIFY `code_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_authorization_codes_permissions`
--
ALTER TABLE `oauth_authorization_codes_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_permissions`
--
ALTER TABLE `oauth_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_tokens`
--
ALTER TABLE `oauth_tokens`
  MODIFY `token_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_tokens_permissions`
--
ALTER TABLE `oauth_tokens_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planner_planifications`
--
ALTER TABLE `planner_planifications`
  MODIFY `planification_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planner_planifications_networks`
--
ALTER TABLE `planner_planifications_networks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planner_planifications_posts`
--
ALTER TABLE `planner_planifications_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planner_planifications_rules`
--
ALTER TABLE `planner_planifications_rules`
  MODIFY `rule_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planner_planifications_rules_meta`
--
ALTER TABLE `planner_planifications_rules_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans_meta`
--
ALTER TABLE `plans_meta`
  MODIFY `meta_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts_meta`
--
ALTER TABLE `posts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referrer_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend`
--
ALTER TABLE `resend`
  MODIFY `resend_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend_meta`
--
ALTER TABLE `resend_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend_rules`
--
ALTER TABLE `resend_rules`
  MODIFY `rule_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss`
--
ALTER TABLE `rss`
  MODIFY `rss_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_accounts`
--
ALTER TABLE `rss_accounts`
  MODIFY `account_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_posts`
--
ALTER TABLE `rss_posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_posts_meta`
--
ALTER TABLE `rss_posts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled`
--
ALTER TABLE `scheduled`
  MODIFY `scheduled_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_stats`
--
ALTER TABLE `scheduled_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `member_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams_roles`
--
ALTER TABLE `teams_roles`
  MODIFY `role_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams_roles_permission`
--
ALTER TABLE `teams_roles_permission`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets_meta`
--
ALTER TABLE `tickets_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_fields`
--
ALTER TABLE `transactions_fields`
  MODIFY `field_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_options`
--
ALTER TABLE `transactions_options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `url_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urls_stats`
--
ALTER TABLE `urls_stats`
  MODIFY `stats_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `users_meta`
--
ALTER TABLE `users_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
