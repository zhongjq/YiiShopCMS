-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 18 2012 г., 11:02
-- Версия сервера: 5.5.28-0ubuntu0.12.04.2
-- Версия PHP: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `enchikiben_fbfde`
--

-- --------------------------------------------------------

--
-- Структура таблицы `boolean_field`
--

CREATE TABLE IF NOT EXISTS `boolean_field` (
  `field_id` int(11) unsigned NOT NULL,
  `default` tinyint(1) unsigned DEFAULT NULL COMMENT 'Поумолчанию',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `category_field`
--

CREATE TABLE IF NOT EXISTS `category_field` (
  `field_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `is_multiple_select` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `datetime_field`
--

CREATE TABLE IF NOT EXISTS `datetime_field` (
  `field_id` int(11) unsigned NOT NULL,
  `type` int(11) unsigned DEFAULT NULL COMMENT 'Тип: дата/время, время, дата',
  `format` varchar(255) DEFAULT NULL COMMENT 'Да',
  `is_multiple_select` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Дата время поле';

-- --------------------------------------------------------

--
-- Структура таблицы `disk`
--

CREATE TABLE IF NOT EXISTS `disk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) DEFAULT NULL,
  `title` text,
  `keywords` text,
  `description` text,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `spikes` tinyint(1) DEFAULT NULL,
  `boom` float DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `diameter` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `double_field`
--

CREATE TABLE IF NOT EXISTS `double_field` (
  `field_id` int(11) unsigned NOT NULL,
  `decimal` int(11) unsigned DEFAULT NULL COMMENT 'От',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

-- --------------------------------------------------------

--
-- Структура таблицы `field_tab`
--

CREATE TABLE IF NOT EXISTS `field_tab` (
  `field_id` int(10) unsigned NOT NULL,
  `tab_id` int(10) unsigned DEFAULT NULL,
  `position` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`field_id`),
  KEY `tab_id` (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `record_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `disc_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `record_id` (`record_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `file_field`
--

CREATE TABLE IF NOT EXISTS `file_field` (
  `field_id` int(10) unsigned NOT NULL DEFAULT '0',
  `file_type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Поле типа файл';

-- --------------------------------------------------------

--
-- Структура таблицы `integer_field`
--

CREATE TABLE IF NOT EXISTS `integer_field` (
  `field_id` int(11) unsigned NOT NULL,
  `min_value` int(11) DEFAULT NULL COMMENT 'От',
  `max_value` int(11) DEFAULT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

-- --------------------------------------------------------

--
-- Структура таблицы `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `list_field`
--

CREATE TABLE IF NOT EXISTS `list_field` (
  `field_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `is_multiple_select` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Множественный выбор списка',
  PRIMARY KEY (`field_id`),
  KEY `fk_list_field_list1_idx` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `list_item`
--

CREATE TABLE IF NOT EXISTS `list_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_list_item_list1_idx` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer`
--

CREATE TABLE IF NOT EXISTS `manufacturer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned NOT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `keywords` text,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_field`
--

CREATE TABLE IF NOT EXISTS `manufacturer_field` (
  `field_id` int(11) unsigned NOT NULL,
  `manufacturer_id` int(10) unsigned DEFAULT NULL,
  `is_multiple_select` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `price_field`
--

CREATE TABLE IF NOT EXISTS `price_field` (
  `field_id` int(11) unsigned NOT NULL,
  `max_value` int(11) unsigned DEFAULT NULL COMMENT 'Максимальное значение',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица продуктов магазина' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `product_field`
--

CREATE TABLE IF NOT EXISTS `product_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `field_type` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `is_mandatory` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_filter` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_column_table` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'используется в заголовке таблицы',
  `unit_name` varchar(255) NOT NULL COMMENT 'Единицы измерения',
  `hint` varchar(255) NOT NULL COMMENT 'Подсказка',
  `is_editing_table_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_column_table_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_field_product1_idx` (`product_id`),
  KEY `is_column_table_admin` (`is_column_table_admin`),
  KEY `is_editing_table_admin` (`is_editing_table_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `record_category`
--

CREATE TABLE IF NOT EXISTS `record_category` (
  `product_id` int(11) unsigned NOT NULL,
  `record_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  KEY `fk_record_category_category1_idx` (`category_id`),
  KEY `fk_record_category_product1_idx` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Структура таблицы `record_datetime`
--

CREATE TABLE IF NOT EXISTS `record_datetime` (
  `product_id` int(11) unsigned NOT NULL,
  `record_id` int(11) unsigned NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`product_id`,`record_id`,`datetime`),
  KEY `fk_record_manufacturer_product1_idx` (`product_id`),
  KEY `record_id` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Структура таблицы `record_list`
--

CREATE TABLE IF NOT EXISTS `record_list` (
  `product_id` int(11) unsigned NOT NULL,
  `record_id` int(11) unsigned NOT NULL,
  `list_item_id` int(11) unsigned NOT NULL,
  KEY `ProductID` (`product_id`),
  KEY `ListItemID` (`list_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Структура таблицы `record_manufacturer`
--

CREATE TABLE IF NOT EXISTS `record_manufacturer` (
  `product_id` int(11) unsigned NOT NULL,
  `record_id` int(11) unsigned NOT NULL,
  `manufacturer_id` int(11) unsigned NOT NULL,
  KEY `manufacturer_id` (`manufacturer_id`),
  KEY `fk_record_manufacturer_product1_idx` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Связь товаров со множественными списками';

-- --------------------------------------------------------

--
-- Структура таблицы `string_field`
--

CREATE TABLE IF NOT EXISTS `string_field` (
  `field_id` int(11) unsigned NOT NULL,
  `min_length` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `max_length` int(3) unsigned NOT NULL DEFAULT '255' COMMENT 'Максимальная длинна',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Строковые поля';

-- --------------------------------------------------------

--
-- Структура таблицы `tab`
--

CREATE TABLE IF NOT EXISTS `tab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `position` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`,`product_id`),
  KEY `fk_tab_product_field1_idx` (`product_id`),
  KEY `fk_tab_field_tab1_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `text_field`
--

CREATE TABLE IF NOT EXISTS `text_field` (
  `field_id` int(11) unsigned NOT NULL,
  `min_length` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `max_length` int(11) unsigned NOT NULL DEFAULT '10000' COMMENT 'Максимальная длинна',
  `rows` int(11) unsigned NOT NULL DEFAULT '5' COMMENT 'Строк',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Текстовые поля';

-- --------------------------------------------------------

--
-- Структура таблицы `tires`
--

CREATE TABLE IF NOT EXISTS `tires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) DEFAULT NULL,
  `title` text,
  `keywords` text,
  `description` text,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `logTest` tinyint(1) DEFAULT NULL,
  `manufacturer` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manufacturer` (`manufacturer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Включен/Выключен',
  `role_id` int(5) unsigned DEFAULT '1' COMMENT 'Номер роли',
  `registration_time` datetime DEFAULT NULL COMMENT 'Дата и время регистрации',
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `status`, `role_id`, `registration_time`, `email`, `password`, `username`) VALUES
(1, 0, 2, '2012-06-30 20:17:00', 'enchikiben@gmail.com', 'a37e9e0ada9d5eef566727a9a8ea36e8', ''),
(2, 0, 1, '2012-09-20 02:41:23', 'test@test.ru', 'test@test.ru', 'test');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `boolean_field`
--
ALTER TABLE `boolean_field`
  ADD CONSTRAINT `boolean_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `category_field`
--
ALTER TABLE `category_field`
  ADD CONSTRAINT `fk_category_field_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_category_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `datetime_field`
--
ALTER TABLE `datetime_field`
  ADD CONSTRAINT `datetime_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `double_field`
--
ALTER TABLE `double_field`
  ADD CONSTRAINT `double_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `field_tab`
--
ALTER TABLE `field_tab`
  ADD CONSTRAINT `field_tab_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `field_tab_ibfk_2` FOREIGN KEY (`tab_id`) REFERENCES `tab` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `file_field`
--
ALTER TABLE `file_field`
  ADD CONSTRAINT `file_field_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `integer_field`
--
ALTER TABLE `integer_field`
  ADD CONSTRAINT `fk_integer_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `list_field`
--
ALTER TABLE `list_field`
  ADD CONSTRAINT `fk_list_field_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_list_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `list_item`
--
ALTER TABLE `list_item`
  ADD CONSTRAINT `fk_list_item_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `manufacturer_field`
--
ALTER TABLE `manufacturer_field`
  ADD CONSTRAINT `fk_manufacturer_field_product_field` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manufacturer_field_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `price_field`
--
ALTER TABLE `price_field`
  ADD CONSTRAINT `fk_price_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_field`
--
ALTER TABLE `product_field`
  ADD CONSTRAINT `fk_product_field_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `record_category`
--
ALTER TABLE `record_category`
  ADD CONSTRAINT `fk_record_category_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_record_category_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `record_datetime`
--
ALTER TABLE `record_datetime`
  ADD CONSTRAINT `record_datetime_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `record_list`
--
ALTER TABLE `record_list`
  ADD CONSTRAINT `fk_record_list_list_item1` FOREIGN KEY (`list_item_id`) REFERENCES `list_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_record_list_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `record_manufacturer`
--
ALTER TABLE `record_manufacturer`
  ADD CONSTRAINT `fk_record_manufacturer_manufacturer1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_record_manufacturer_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `string_field`
--
ALTER TABLE `string_field`
  ADD CONSTRAINT `fk_string_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tab`
--
ALTER TABLE `tab`
  ADD CONSTRAINT `fk_tab_product_field1` FOREIGN KEY (`product_id`) REFERENCES `product_field` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `text_field`
--
ALTER TABLE `text_field`
  ADD CONSTRAINT `fk_text_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tires`
--
ALTER TABLE `tires`
  ADD CONSTRAINT `tires_ibfk_1` FOREIGN KEY (`manufacturer`) REFERENCES `manufacturer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
