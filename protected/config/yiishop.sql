-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Хост: mysql0.db.koding.com
-- Время создания: Ноя 21 2012 г., 10:21
-- Версия сервера: 5.1.61-log
-- Версия PHP: 5.3.3

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

--
-- Дамп данных таблицы `boolean_field`
--

INSERT INTO `boolean_field` (`field_id`, `default`) VALUES
(9, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `bus`
--

CREATE TABLE IF NOT EXISTS `bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) DEFAULT NULL,
  `title` text,
  `keywords` text,
  `description` text,
  `manufacturer` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `season` int(11) DEFAULT NULL,
  `typeAuto` int(11) DEFAULT NULL,
  `profileWidth` int(11) DEFAULT NULL,
  `profileHeight` int(11) DEFAULT NULL,
  `construction` int(11) DEFAULT NULL,
  `methodSealing` int(11) DEFAULT NULL,
  `spikes` tinyint(1) DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `importID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `bus`
--

INSERT INTO `bus` (`id`, `alias`, `title`, `keywords`, `description`, `manufacturer`, `model`, `season`, `typeAuto`, `profileWidth`, `profileHeight`, `construction`, `methodSealing`, `spikes`, `price`, `quantity`, `importID`) VALUES
(1, '', '', '', '', 6, 'Ice Cruiser 7000', 1, 4, 14, 39, 81, 82, 1, '1000.00', 2, NULL);

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
  `title` text,
  `keywords` text,
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

--
-- Дамп данных таблицы `field_tab`
--

INSERT INTO `field_tab` (`field_id`, `tab_id`, `position`) VALUES
(1, NULL, 0),
(2, NULL, 1),
(3, NULL, 8),
(4, NULL, 7),
(5, NULL, 6),
(6, NULL, 5),
(7, NULL, 4),
(8, NULL, 3),
(9, NULL, 10),
(10, 1, NULL),
(11, NULL, 2),
(12, 2, NULL),
(13, 2, NULL);

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

--
-- Дамп данных таблицы `file_field`
--

INSERT INTO `file_field` (`field_id`, `file_type`) VALUES
(10, 0);

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

--
-- Дамп данных таблицы `integer_field`
--

INSERT INTO `integer_field` (`field_id`, `min_value`, `max_value`) VALUES
(12, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `list`
--

INSERT INTO `list` (`id`, `name`) VALUES
(1, 'Сезонность'),
(2, 'Тип автомобиля'),
(3, 'Ширина профиля'),
(4, 'Высота профиля'),
(5, 'Диаметр'),
(6, 'Индекс скорости'),
(7, 'Индекс нагрузки'),
(8, 'Способ герметизации'),
(9, 'Конструкция');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `list_field`
--

INSERT INTO `list_field` (`field_id`, `list_id`, `is_multiple_select`) VALUES
(3, 1, 0),
(4, 2, 0),
(5, 3, 0),
(6, 4, 0),
(7, 9, 0),
(8, 8, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=84 ;

--
-- Дамп данных таблицы `list_item`
--

INSERT INTO `list_item` (`id`, `list_id`, `status`, `priority`, `name`) VALUES
(1, 1, 1, 0, 'зимние'),
(2, 1, 1, 0, 'летние'),
(3, 1, 1, 0, 'всесезонные'),
(4, 2, 1, 0, 'легковой'),
(5, 2, 1, 0, 'грузовой'),
(6, 3, 1, 0, '125'),
(7, 3, 1, 0, '135'),
(8, 3, 1, 0, '145'),
(9, 3, 1, 0, '155'),
(10, 3, 1, 0, '165'),
(11, 3, 1, 0, '175'),
(12, 3, 1, 0, '185'),
(13, 3, 1, 0, '195'),
(14, 3, 1, 0, '205'),
(15, 3, 1, 0, '215'),
(16, 3, 1, 0, '225'),
(17, 3, 1, 0, '235'),
(18, 3, 1, 0, '245'),
(19, 3, 1, 0, '255'),
(20, 3, 1, 0, '265'),
(21, 3, 1, 0, '275'),
(22, 3, 1, 0, '285'),
(23, 3, 1, 0, '295'),
(24, 3, 1, 0, '305'),
(25, 3, 1, 0, '315'),
(26, 3, 1, 0, '325'),
(27, 3, 1, 0, '335'),
(28, 3, 1, 0, '345'),
(29, 3, 1, 0, '355'),
(30, 3, 1, 0, '365'),
(31, 3, 1, 0, '375'),
(32, 3, 1, 0, '395'),
(33, 4, 1, 0, '25'),
(34, 4, 1, 0, '30'),
(35, 4, 1, 0, '35'),
(36, 4, 1, 0, '40'),
(37, 4, 1, 0, '45'),
(38, 4, 1, 0, '50'),
(39, 4, 1, 0, '55'),
(40, 4, 1, 0, '60'),
(41, 4, 1, 0, '65'),
(42, 4, 1, 0, '70'),
(43, 4, 1, 0, '75'),
(44, 4, 1, 0, '80'),
(45, 4, 1, 0, '85'),
(46, 4, 1, 0, '90'),
(47, 4, 1, 0, '95'),
(48, 4, 1, 0, '105'),
(49, 5, 1, 0, '12'),
(50, 5, 1, 0, '13'),
(51, 5, 1, 0, '14'),
(52, 5, 1, 0, '15'),
(53, 5, 1, 0, '16'),
(54, 5, 1, 0, '16.5'),
(55, 5, 1, 0, '17'),
(56, 5, 1, 0, '18'),
(57, 5, 1, 0, '19'),
(58, 5, 1, 0, '20'),
(59, 5, 1, 0, '21'),
(60, 5, 1, 0, '22'),
(61, 5, 1, 0, '23'),
(62, 5, 1, 0, '24'),
(63, 5, 1, 0, '26'),
(64, 5, 1, 0, '28'),
(65, 6, 1, 0, 'H (до 210 км/ч)'),
(66, 6, 1, 0, 'J (до 100 км/ч)'),
(67, 6, 1, 0, 'K (до 110 км/ч)'),
(68, 6, 1, 0, 'L (до 120 км/ч)'),
(69, 6, 1, 0, 'M (до 130 км/ч)'),
(70, 6, 1, 0, 'N (до 140 км/ч)'),
(71, 6, 1, 0, 'P (до 150 км/ч)'),
(72, 6, 1, 0, 'Q (до 160 км/ч)'),
(73, 6, 1, 0, 'R (до 170 км/ч)'),
(74, 6, 1, 0, 'S (до 180 км/ч)'),
(75, 6, 1, 0, 'T (до 190 км/ч)'),
(76, 6, 1, 0, 'V (до 240 км/ч)'),
(77, 6, 1, 0, 'W (до 270 км/ч)'),
(78, 6, 1, 0, 'Y (до 300 км/ч)'),
(79, 6, 1, 0, 'Z/ZR (свыше 240 км/ч)'),
(80, 9, 1, 0, 'диагональные'),
(81, 9, 1, 0, 'радиальные'),
(82, 8, 1, 0, 'бескамерные'),
(83, 8, 1, 0, 'камерные');

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
  `title` text,
  `description` text,
  `keywords` text,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `title`, `description`, `keywords`, `logo`) VALUES
(1, 1, 1, 14, 1, 1, 'bus', 'Шины', '', '', '', ''),
(2, 1, 2, 3, 2, 1, 'amtel', 'Amtel', '', '', '', 'amtel.png'),
(3, 1, 4, 5, 2, 1, 'barum', 'Barum', '', '', '', 'barum.gif'),
(4, 1, 6, 7, 2, 1, 'bfgoodrich', 'BFGoodrich', '', '', '', 'bfgoodrich.png'),
(5, 1, 8, 9, 2, 1, 'brasa', 'Brasa', '', '', '', 'brasa.png'),
(6, 1, 10, 11, 2, 1, 'bridgestone', 'Bridgestone', 'Bridgestone', '', '', 'bridgestone.gif'),
(7, 1, 12, 13, 2, 1, 'nokian', 'Nokian', 'nokian', 'nokian', 'nokian', '');

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

--
-- Дамп данных таблицы `manufacturer_field`
--

INSERT INTO `manufacturer_field` (`field_id`, `manufacturer_id`, `is_multiple_select`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `price_field`
--

CREATE TABLE IF NOT EXISTS `price_field` (
  `field_id` int(11) unsigned NOT NULL,
  `max_value` int(11) unsigned DEFAULT NULL COMMENT 'Максимальное значение',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

--
-- Дамп данных таблицы `price_field`
--

INSERT INTO `price_field` (`field_id`, `max_value`) VALUES
(11, 0);

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
(1, 1, 'Шины', 'bus', 'Шины', 'Шины', 'Шины');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `product_field`
--

INSERT INTO `product_field` (`id`, `position`, `product_id`, `field_type`, `name`, `alias`, `is_mandatory`, `is_filter`, `is_column_table`, `unit_name`, `hint`, `is_editing_table_admin`, `is_column_table_admin`) VALUES
(1, 0, 1, 7, 'Производитель', 'manufacturer', 1, 1, 1, '', '', 0, 1),
(2, 1, 1, 2, 'Наименование модели', 'model', 1, 1, 1, '', '', 0, 1),
(3, 5, 1, 5, 'Сезонность', 'season', 0, 1, 1, '', '', 0, 0),
(4, 6, 1, 5, 'Тип автомобиля', 'typeAuto', 0, 1, 1, '', '', 0, 0),
(5, 7, 1, 5, 'Ширина профиля', 'profileWidth', 0, 0, 1, 'мм', '', 0, 0),
(6, 8, 1, 5, 'Высота профиля', 'profileHeight', 0, 1, 1, '', '', 0, 0),
(7, 9, 1, 5, 'Конструкция', 'construction', 0, 1, 1, '', '', 0, 0),
(8, 10, 1, 5, 'Способ герметизации', 'methodSealing', 0, 1, 1, '', '', 0, 0),
(9, 11, 1, 10, 'Шипы', 'spikes', 0, 1, 1, '', '', 0, 0),
(10, 12, 1, 8, 'Изображения', 'image', 0, 0, 1, '', '', 0, 0),
(11, 2, 1, 3, 'Цена', 'price', 1, 1, 1, 'руб.', '', 1, 1),
(12, 3, 1, 1, 'Количество', 'quantity', 0, 0, 1, 'ед.', '', 1, 1),
(13, 4, 1, 2, 'Номер импорта', 'importID', 0, 0, 1, '', '', 0, 0);

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

--
-- Дамп данных таблицы `string_field`
--

INSERT INTO `string_field` (`field_id`, `min_length`, `max_length`) VALUES
(2, 0, 255),
(13, 0, 255);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tab`
--

INSERT INTO `tab` (`id`, `product_id`, `position`, `name`) VALUES
(1, 1, NULL, 'Изображения'),
(2, 1, NULL, 'Склад');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
