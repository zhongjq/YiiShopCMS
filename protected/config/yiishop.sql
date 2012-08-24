-- phpMyAdmin SQL Dump
-- version 3.5.2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 24 2012 г., 21:56
-- Версия сервера: 5.5.24-0ubuntu0.12.04.1
-- Версия PHP: 5.3.10-1ubuntu3.2

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `description`) VALUES
(1, 1, 1, 6, 1, 1, 'tires', 'Шины', 'Шины'),
(2, 1, 2, 3, 2, 1, 'leto', 'Лето', ''),
(3, 1, 4, 5, 2, 1, 'zima', 'Зима', '');

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

--
-- Дамп данных таблицы `category_field`
--

INSERT INTO `category_field` (`field_id`, `category_id`, `is_multiple_select`) VALUES
(4, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `image_field`
--

CREATE TABLE IF NOT EXISTS `image_field` (
  `field_id` int(11) unsigned NOT NULL,
  `is_multiple_select` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Множественный выбор картинок',
  `quantity` int(11) unsigned NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Поля картинок';

-- --------------------------------------------------------

--
-- Структура таблицы `image_field_parameter`
--

CREATE TABLE IF NOT EXISTS `image_field_parameter` (
  `field_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL,
  KEY `fk_image_field_parameter_image_field1_idx` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица параметров поля изображения';

-- --------------------------------------------------------

--
-- Структура таблицы `integer_field`
--

CREATE TABLE IF NOT EXISTS `integer_field` (
  `field_id` int(11) unsigned NOT NULL,
  `min_value` int(11) unsigned DEFAULT NULL COMMENT 'От',
  `max_value` int(11) unsigned DEFAULT NULL COMMENT 'Да',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `list`
--

INSERT INTO `list` (`id`, `name`) VALUES
(1, 'Индекс максимальной скорости'),
(2, 'Тип автомобиля'),
(3, 'Диаметр'),
(4, 'Высота профиля'),
(5, 'Ширина профиля');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=77 ;

--
-- Дамп данных таблицы `list_item`
--

INSERT INTO `list_item` (`id`, `list_id`, `status`, `priority`, `name`) VALUES
(1, 1, 1, 0, 'H (до 210 км/ч)\r'),
(2, 1, 1, 0, 'J (до 100 км/ч)\r'),
(3, 1, 1, 0, 'K (до 110 км/ч)\r'),
(4, 1, 1, 0, 'L (до 120 км/ч)\r'),
(5, 1, 1, 0, 'M (до 130 км/ч)\r'),
(6, 1, 1, 0, 'N (до 140 км/ч)\r'),
(7, 1, 1, 0, 'P (до 150 км/ч)\r'),
(8, 1, 1, 0, 'Q (до 160 км/ч)\r'),
(9, 1, 1, 0, 'R (до 170 км/ч)\r'),
(10, 1, 1, 0, 'S (до 180 км/ч)\r'),
(11, 1, 1, 0, 'T (до 190 км/ч)\r'),
(12, 1, 1, 0, 'V (до 240 км/ч)\r'),
(13, 1, 1, 0, 'W (до 270 км/ч)\r'),
(14, 1, 1, 0, 'Y (до 300 км/ч)\r'),
(15, 1, 1, 0, 'Z/ZR (свыше 240 км/ч)'),
(16, 2, 1, 0, 'внедорожник\r'),
(17, 2, 1, 0, 'легковой'),
(18, 3, 1, 0, '12\r'),
(19, 3, 1, 0, '13\r'),
(20, 3, 1, 0, '14\r'),
(21, 3, 1, 0, '15\r'),
(22, 3, 1, 0, '16\r'),
(23, 3, 1, 0, '16.5\r'),
(24, 3, 1, 0, '17\r'),
(25, 3, 1, 0, '18\r'),
(26, 3, 1, 0, '19\r'),
(27, 3, 1, 0, '20\r'),
(28, 3, 1, 0, '21\r'),
(29, 3, 1, 0, '22\r'),
(30, 3, 1, 0, '23\r'),
(31, 3, 1, 0, '24\r'),
(32, 3, 1, 0, '26\r'),
(33, 3, 1, 0, '28'),
(34, 4, 1, 0, '25\r'),
(35, 4, 1, 0, '30\r'),
(36, 4, 1, 0, '35\r'),
(37, 4, 1, 0, '40\r'),
(38, 4, 1, 0, '45\r'),
(39, 4, 1, 0, '50\r'),
(40, 4, 1, 0, '55\r'),
(41, 4, 1, 0, '60\r'),
(42, 4, 1, 0, '65\r'),
(43, 4, 1, 0, '70\r'),
(44, 4, 1, 0, '75\r'),
(45, 4, 1, 0, '80\r'),
(46, 4, 1, 0, '85\r'),
(47, 4, 1, 0, '90\r'),
(48, 4, 1, 0, '95\r'),
(49, 4, 1, 0, '105'),
(50, 5, 1, 0, '125\r'),
(51, 5, 1, 0, '135\r'),
(52, 5, 1, 0, '145\r'),
(53, 5, 1, 0, '155\r'),
(54, 5, 1, 0, '165\r'),
(55, 5, 1, 0, '175\r'),
(56, 5, 1, 0, '185\r'),
(57, 5, 1, 0, '195\r'),
(58, 5, 1, 0, '205\r'),
(59, 5, 1, 0, '215\r'),
(60, 5, 1, 0, '225\r'),
(61, 5, 1, 0, '235\r'),
(62, 5, 1, 0, '245\r'),
(63, 5, 1, 0, '255\r'),
(64, 5, 1, 0, '265\r'),
(65, 5, 1, 0, '275\r'),
(66, 5, 1, 0, '285\r'),
(67, 5, 1, 0, '295\r'),
(68, 5, 1, 0, '305\r'),
(69, 5, 1, 0, '315\r'),
(70, 5, 1, 0, '325\r'),
(71, 5, 1, 0, '335\r'),
(72, 5, 1, 0, '345\r'),
(73, 5, 1, 0, '355\r'),
(74, 5, 1, 0, '365\r'),
(75, 5, 1, 0, '375\r'),
(76, 5, 1, 0, '395');

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
  `description` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `description`, `logo`) VALUES
(1, 7, 2, 2, 2, 1, 'bridgestone', 'Bridgestone', '', ''),
(2, 7, 2, 2, 2, 1, 'brasa', 'Brasa', '', ''),
(3, 7, 2, 2, 2, 1, 'continental', 'Continental', '', ''),
(4, 7, 2, 2, 2, 1, 'cordiant', 'Cordiant', '', ''),
(5, 7, 2, 2, 2, 1, 'dunlop', 'Dunlop', '', ''),
(6, 7, 2, 2, 2, 1, 'gislaved', 'Gislaved', '', ''),
(7, 7, 1, 3, 1, 1, 'tires', 'Шины', '', ''),
(8, 8, 1, 2, 1, 1, 'disc', 'Диски', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `manufacturer_field`
--

CREATE TABLE IF NOT EXISTS `manufacturer_field` (
  `field_id` int(11) unsigned NOT NULL,
  `is_multiple_select` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `manufacturer_field`
--

INSERT INTO `manufacturer_field` (`field_id`, `is_multiple_select`) VALUES
(3, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `price_field`
--

CREATE TABLE IF NOT EXISTS `price_field` (
  `field_id` int(11) unsigned NOT NULL,
  `max_value` int(11) unsigned DEFAULT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

--
-- Дамп данных таблицы `price_field`
--

INSERT INTO `price_field` (`field_id`, `max_value`) VALUES
(2, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица продуктов магазина' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `status`, `name`, `alias`, `title`, `keywords`, `description`) VALUES
(1, 1, 'Шины', 'tires', 'Шины', '', '');

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
  PRIMARY KEY (`id`),
  KEY `fk_product_field_product1_idx` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `product_field`
--

INSERT INTO `product_field` (`id`, `position`, `product_id`, `field_type`, `name`, `alias`, `is_mandatory`, `is_filter`, `is_column_table`, `unit_name`, `hint`) VALUES
(1, 0, 1, 2, 'Наименование', 'name', 1, 0, 1, '', 'Наименование шины'),
(2, 0, 1, 3, 'Цена', 'price', 1, 1, 1, 'р.', ''),
(3, 0, 1, 7, 'Производитель', 'manufacturer', 1, 1, 1, '', ''),
(4, 0, 1, 6, 'Категория', 'category', 1, 0, 1, '', '');

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

--
-- Дамп данных таблицы `string_field`
--

INSERT INTO `string_field` (`field_id`, `min_length`, `max_length`) VALUES
(1, 0, 255);

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
  `manufacturer` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tires`
--

INSERT INTO `tires` (`id`, `alias`, `title`, `keywords`, `description`, `name`, `price`, `manufacturer`, `category`) VALUES
(1, '', NULL, NULL, NULL, 'Bridgestone Ice Cruiser 7000 235/70 R16 T', 7200.00, 1, 3),
(2, '', NULL, NULL, NULL, 'Bridgestone IC7000 185/65 R15 88T', 3690.00, 1, 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей' AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `status`, `role_id`, `registration_time`, `email`, `password`, `username`) VALUES
(1, 0, 2, '2012-06-30 20:17:00', 'enchikiben@gmail.com', 'a37e9e0ada9d5eef566727a9a8ea36e8', NULL);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category_field`
--
ALTER TABLE `category_field`
  ADD CONSTRAINT `fk_category_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_category_field_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `image_field`
--
ALTER TABLE `image_field`
  ADD CONSTRAINT `fk_image_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `image_field_parameter`
--
ALTER TABLE `image_field_parameter`
  ADD CONSTRAINT `fk_image_field_parameter_image_field1` FOREIGN KEY (`field_id`) REFERENCES `image_field` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `integer_field`
--
ALTER TABLE `integer_field`
  ADD CONSTRAINT `fk_integer_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `list_field`
--
ALTER TABLE `list_field`
  ADD CONSTRAINT `fk_list_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_list_field_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `list_item`
--
ALTER TABLE `list_item`
  ADD CONSTRAINT `fk_list_item_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `manufacturer_field`
--
ALTER TABLE `manufacturer_field`
  ADD CONSTRAINT `fk_manufacturer_field_product_field` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_record_category_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_record_category_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Ограничения внешнего ключа таблицы `text_field`
--
ALTER TABLE `text_field`
  ADD CONSTRAINT `fk_text_field_product_field1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
