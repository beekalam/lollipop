-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2018 at 06:33 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(20, 'qqq'),
(3, 'شهر بازی'),
(2, 'پارکینگ'),
(1, 'کافی شاپ');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
`id` int(11) NOT NULL,
  `first_name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `cardid` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `checkin` int(11) DEFAULT NULL COMMENT 'unix timestamp',
  `checkout` int(11) DEFAULT NULL COMMENT 'unix timestamp',
  `mobile` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `card_expire_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `card_canceled` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9247 ;


--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
`id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `value`) VALUES
(1, 2),
(2, 3),
(3, 4),
(5, 6),
(6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
`id` int(11) NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 NOT NULL,
  `price` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='prices table' AUTO_INCREMENT=39 ;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `description`, `price`, `cat_id`) VALUES
(22, 'پارک ۱ ساعت', 30000, 2),
(33, 'گریم', 10000, 3),
(34, 'دستگاه vr', 5000, 3),
(35, 'دستگاه تکان دهنده', 5000, 3),
(36, 'test1', 333, 3),
(37, 'teet3', 55555, 3),
(38, 'testsss', 5555, 20);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `value` text
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `value`) VALUES
(28, 'role1', '{"add_customer":true,"view_settings":true,"view_durations":true,"add_duration":true,"delete_duration":true,"edit_duration":true,"view_extra_service":false,"add_extra_service":false,"delete_extra_service":true,"edit_extra_service":true,"view_categories":false,"add_category":false,"delete_category":true,"view_discounts":false,"add_discount":true,"delete_discount":false,"view_sales":false,"view_users":false,"change_user_password":false}');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `value` varchar(256) DEFAULT NULL,
  `description` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `unit` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
`id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `checkin` int(11) NOT NULL,
  `checkout` int(11) NOT NULL,
  `extra_services` varchar(8000) CHARACTER SET utf8 DEFAULT NULL,
  `vorodi` int(11) NOT NULL,
  `extras_total` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `total_with_discount` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=798 ;



-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `first_name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(125) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'unix timestamp',
  `is_active` varchar(3) NOT NULL COMMENT 'yes or no',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(200) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `img` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `created_date`, `is_active`, `isadmin`, `password`, `user_name`, `role_id`) VALUES(14, 'محمد رضا', 'منصوری', 'a@a.com', '2018-02-08 07:44:11', 'yes', 1, '5f6955d227a320c7f1f6c7da2a6d96a851a8118f', 'admin', 0);
-- Table structure for table `users_extra`
--

CREATE TABLE IF NOT EXISTS `users_extra` (
`id` int(11) NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE IF NOT EXISTS `user_roles` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vorodi`
--

CREATE TABLE IF NOT EXISTS `vorodi` (
`id` int(11) NOT NULL,
  `duration` float NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 NOT NULL,
  `seconds` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `vorodi`
--

INSERT INTO `vorodi` (`id`, `duration`, `description`, `seconds`, `price`) VALUES
(17, 3600, 'تا ۱ ساعت', NULL, 30000),
(28, 300, 'تا ۵ دقیقه', NULL, 0),
(29, 900, 'تا ۱۵ دقیقه', NULL, 10000),
(30, 3540, '60', NULL, 56),
(31, 60, 'تا ۱ دقیقه', NULL, 500);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `categories_name_uindex` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `discounts_value_uindex` (`value`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `roles_name_uindex` (`name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `settings_name_uindex` (`name`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_user_name_uindex` (`user_name`);

--
-- Indexes for table `users_extra`
--
ALTER TABLE `users_extra`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vorodi`
--
ALTER TABLE `vorodi`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `vorodi_duration_uindex` (`duration`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9247;
--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=798;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users_extra`
--
ALTER TABLE `users_extra`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vorodi`
--
ALTER TABLE `vorodi`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;