-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: mysql0.db.koding.com
-- Generation Time: Aug 23, 2012 at 05:40 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `description`) VALUES
(1, 2, 2, 3, 2, 1, 'winter-tires', 'Зима', 'Зимние шины'),
(2, 2, 1, 10, 1, 1, 'tires', 'Шины', 'Шины'),
(3, 2, 4, 5, 2, 1, 'leto', 'Лето', 'Лето'),
(4, 2, 6, 7, 2, 1, 'vsesezon', 'Всесезонная', 'Всесезонная'),
(5, 2, 8, 9, 2, 1, 'track-tyres', 'Грузовые шины', 'Грузовые шины');

-- --------------------------------------------------------

--
-- Table structure for table `category_field`
--

CREATE TABLE IF NOT EXISTS `category_field` (
  `field_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_multiple_select` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_field`
--

INSERT INTO `category_field` (`field_id`, `category_id`, `is_multiple_select`) VALUES
(7, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `image_field`
--

CREATE TABLE IF NOT EXISTS `image_field` (
  `field_id` int(11) NOT NULL,
  `is_multiple_select` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Множественный выбор картинок',
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Поля картинок';

-- --------------------------------------------------------

--
-- Table structure for table `image_field_parameter`
--

CREATE TABLE IF NOT EXISTS `image_field_parameter` (
  `field_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица параметров поля изображения';

-- --------------------------------------------------------

--
-- Table structure for table `integer_field`
--

CREATE TABLE IF NOT EXISTS `integer_field` (
  `field_id` int(11) NOT NULL,
  `min_value` int(11) DEFAULT NULL COMMENT 'От',
  `max_value` int(11) DEFAULT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

--
-- Dumping data for table `integer_field`
--

INSERT INTO `integer_field` (`field_id`, `min_value`, `max_value`) VALUES
(3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`id`, `name`) VALUES
(1, 'Диаметр');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `list_field`
--

INSERT INTO `list_field` (`field_id`, `list_id`, `is_multiple_select`) VALUES
(5, 1, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=18 ;

--
-- Dumping data for table `list_item`
--

INSERT INTO `list_item` (`id`, `list_id`, `status`, `priority`, `name`) VALUES
(1, 1, 1, 0, '12'),
(2, 1, 1, 0, '13\r'),
(3, 1, 1, 0, '14\r'),
(4, 1, 1, 0, '15\r'),
(5, 1, 1, 0, '16\r'),
(6, 1, 1, 0, '16.5\r'),
(7, 1, 1, 0, '17\r'),
(8, 1, 1, 0, '18\r'),
(9, 1, 1, 0, '19\r'),
(10, 1, 1, 0, '20\r'),
(11, 1, 1, 0, '21\r'),
(12, 1, 1, 0, '22\r'),
(13, 1, 1, 0, '23\r'),
(14, 1, 1, 0, '24\r'),
(15, 1, 1, 0, '26\r'),
(16, 1, 1, 0, '28');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `status`, `alias`, `name`, `description`, `logo`) VALUES
(1, 1, 'amtel', 'Amtel NordMaster', 'Amtel NordMaster', ''),
(2, 1, 'barum', 'Barum', 'Barum', ''),
(3, 1, 'bfgoodrich', 'BFGoodrich', 'BFGoodrich', ''),
(4, 1, 'brasa', 'Brasa', 'Brasa', ''),
(5, 1, 'bridgestone', 'Bridgestone', 'Bridgestone', ''),
(6, 1, 'continental', 'Continental', 'Continental', ''),
(7, 1, 'cordiant', 'Cordiant', 'Cordiant', ''),
(8, 1, 'dunlop', 'Dunlop', 'Dunlop', ''),
(9, 1, 'gislaved', 'Gislaved', 'Gislaved', '');

-- --------------------------------------------------------

--
-- Table structure for table `price_field`
--

CREATE TABLE IF NOT EXISTS `price_field` (
  `field_id` int(11) NOT NULL,
  `max_value` int(11) DEFAULT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

--
-- Dumping data for table `price_field`
--

INSERT INTO `price_field` (`field_id`, `max_value`) VALUES
(2, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `product_field`
--

INSERT INTO `product_field` (`id`, `position`, `product_id`, `field_type`, `name`, `alias`, `is_mandatory`, `is_filter`, `is_column_table`, `unit_name`, `hint`) VALUES
(1, 0, 1, 2, 'Наименование', 'name', 1, 0, 1, '', ''),
(2, 0, 1, 3, 'Цена', 'price', 1, 1, 1, '', ''),
(3, 0, 1, 1, 'Количество на складе', 'quantity', 0, 1, 1, '', ''),
(4, 0, 1, 4, 'Описание', 'description', 0, 0, 0, '', ''),
(5, 0, 1, 5, 'Диаметр', 'diameter', 0, 1, 1, '', ''),
(7, 0, 1, 6, 'Категория', 'category', 0, 0, 1, '', '');

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

--
-- Dumping data for table `string_field`
--

INSERT INTO `string_field` (`field_id`, `min_length`, `max_length`) VALUES
(1, 0, 255);

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

--
-- Dumping data for table `text_field`
--

INSERT INTO `text_field` (`field_id`, `min_length`, `max_length`, `rows`) VALUES
(4, 0, 10000, 5);

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
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `diameter` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
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
-- Constraints for table `category_field`
--
ALTER TABLE `category_field`
  ADD CONSTRAINT `category_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image_field`
--
ALTER TABLE `image_field`
  ADD CONSTRAINT `image_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image_field_parameter`
--
ALTER TABLE `image_field_parameter`
  ADD CONSTRAINT `image_field_parameter_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `image_field` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `list_field`
--
ALTER TABLE `list_field`
  ADD CONSTRAINT `list_field_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `list_item`
--
ALTER TABLE `list_item`
  ADD CONSTRAINT `list_item_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_field`
--
ALTER TABLE `product_field`
  ADD CONSTRAINT `product_field_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `record_category`
--
ALTER TABLE `record_category`
  ADD CONSTRAINT `record_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `record_category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `string_field`
--
ALTER TABLE `string_field`
  ADD CONSTRAINT `string_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `text_field`
--
ALTER TABLE `text_field`
  ADD CONSTRAINT `text_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
