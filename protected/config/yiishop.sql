-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: mysql0.db.koding.com
-- Generation Time: Aug 20, 2012 at 06:14 AM
-- Server version: 5.1.61-log
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `enchikiben_fbfde`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `description`) VALUES
(1, 2, 2, 3, 2, 1, 'winter-tires', 'Зима', 'Зимние шины'),
(2, 2, 1, 8, 1, 1, 'tires', 'Шины', 'Шины'),
(3, 2, 4, 5, 2, 1, 'leto', 'Лето', 'Лето'),
(4, 2, 6, 7, 2, 1, 'vsesezon', 'Всесезонная', 'Всесезонная');

-- --------------------------------------------------------

--
-- Table structure for table `integer_field`
--

CREATE TABLE IF NOT EXISTS `integer_field` (
  `field_id` int(11) NOT NULL,
  `min_value` int(11) NOT NULL COMMENT 'От',
  `max_value` int(11) NOT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_field`
--

CREATE TABLE IF NOT EXISTS `list_field` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `is_multiple_select` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Множественный выбор списка',
  PRIMARY KEY (`field_id`),
  KEY `list_id` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_item`
--

CREATE TABLE IF NOT EXISTS `list_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE IF NOT EXISTS `manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `status`, `alias`, `name`, `description`, `logo`) VALUES
(1, 1, 'amtel', 'Amtel NordMaster', 'Amtel NordMaster', ''),
(2, 1, 'barum', 'Barum', 'Barum', ''),
(3, 1, 'bfgoodrich', 'BFGoodrich', 'BFGoodrich', '');

-- --------------------------------------------------------

--
-- Table structure for table `price_field`
--

CREATE TABLE IF NOT EXISTS `price_field` (
  `field_id` int(11) NOT NULL,
  `max_value` int(11) NOT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица продуктов магазина' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `status`, `name`, `alias`, `title`, `keywords`, `description`) VALUES
(1, 1, 'Шины', 'tires', 'Шины', 'Шины', 'Шины');

-- --------------------------------------------------------

--
-- Table structure for table `product_field`
--

CREATE TABLE IF NOT EXISTS `product_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `field_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_filter` tinyint(1) NOT NULL DEFAULT '0',
  `is_column_table` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'используется в заголовке таблицы',
  `unit_name` varchar(255) NOT NULL COMMENT 'Единицы измерения',
  `hint` varchar(255) NOT NULL COMMENT 'Подсказка',
  PRIMARY KEY (`id`),
  KEY `ProductID` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `record_category`
--

CREATE TABLE IF NOT EXISTS `record_category` (
  `product_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Table structure for table `record_list`
--

CREATE TABLE IF NOT EXISTS `record_list` (
  `product_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `list_item_id` int(11) NOT NULL,
  KEY `ProductID` (`product_id`),
  KEY `ListItemID` (`list_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Table structure for table `string_field`
--

CREATE TABLE IF NOT EXISTS `string_field` (
  `field_id` int(11) NOT NULL,
  `min_length` int(3) NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `max_length` int(3) NOT NULL DEFAULT '255' COMMENT 'Максимальная длинна',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Строковые поля';

-- --------------------------------------------------------

--
-- Table structure for table `text_field`
--

CREATE TABLE IF NOT EXISTS `text_field` (
  `field_id` int(11) NOT NULL,
  `min_length` int(11) NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `max_length` int(11) NOT NULL DEFAULT '10000' COMMENT 'Максимальная длинна',
  `rows` int(11) NOT NULL DEFAULT '5' COMMENT 'Строк',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Текстовые поля';

-- --------------------------------------------------------

--
-- Table structure for table `tires`
--

CREATE TABLE IF NOT EXISTS `tires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) DEFAULT NULL,
  `title` text,
  `keywords` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Включен/Выключен',
  `role_id` int(5) DEFAULT '1' COMMENT 'Номер роли',
  `registration_time` datetime DEFAULT NULL COMMENT 'Дата и время регистрации',
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `status`, `role_id`, `registration_time`, `email`, `password`, `username`) VALUES
(1, 0, 2, '2012-06-30 20:17:00', 'enchikiben@gmail.com', 'a37e9e0ada9d5eef566727a9a8ea36e8', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `list_field`
--
ALTER TABLE `list_field`
  ADD CONSTRAINT `list_field_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `list_item`
--
ALTER TABLE `list_item`
  ADD CONSTRAINT `list_item_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_field`
--
ALTER TABLE `product_field`
  ADD CONSTRAINT `product_field_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `record_category`
--
ALTER TABLE `record_category`
  ADD CONSTRAINT `record_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `record_category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
