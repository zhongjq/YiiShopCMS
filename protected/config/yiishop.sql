-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Хост: mysql0.db.koding.com
-- Время создания: Окт 17 2012 г., 10:39
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
(21, NULL),
(32, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `root`, `lft`, `rgt`, `level`, `status`, `alias`, `name`, `description`) VALUES
(1, 1, 1, 6, 1, 1, 'tires', 'Шины', 'Шины'),
(2, 1, 2, 3, 2, 1, 'leto', 'Лето', ''),
(3, 1, 4, 5, 2, 1, 'zima', 'Зима', ''),
(4, 4, 1, 8, 1, 1, 'disk', 'Диски', ''),
(5, 4, 2, 3, 2, 1, 'kov', 'Кованые', ''),
(6, 4, 4, 5, 2, 1, 'lit', 'Литые', ''),
(7, 4, 6, 7, 2, 1, 'sbo', 'Cборные', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Дамп данных таблицы `disk`
--

INSERT INTO `disk` (`id`, `alias`, `title`, `keywords`, `description`, `name`, `price`, `spikes`, `boom`, `count`, `diameter`) VALUES
(1, '', '', '', '', 'Диск 1', '1111.00', 1, 1, NULL, 34),
(2, '', '', '', '', 'Диск 2', '222.00', 1, 2, NULL, NULL),
(3, '', '', '', '', 'Диск 3', '333.00', 0, 5, NULL, NULL),
(4, '', '', '', '', 'Диск 4', '444.00', 0, 6, NULL, NULL),
(7, '', '', '', '', 'Диск 2', '555.00', 0, NULL, NULL, NULL),
(22, '', '', '', '', 'Диск 2', '666.00', 0, 3, NULL, NULL),
(23, '', '', '', '', 'Диск тест 1', '777.00', NULL, NULL, NULL, NULL),
(24, '', '', '', '', 'Диск тест 2', '888.00', NULL, 1.2, NULL, NULL),
(25, NULL, NULL, NULL, NULL, 'Диск тест 4', '999.00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `double_field`
--

CREATE TABLE IF NOT EXISTS `double_field` (
  `field_id` int(11) unsigned NOT NULL,
  `decimal` int(11) unsigned DEFAULT NULL COMMENT 'От',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

--
-- Дамп данных таблицы `double_field`
--

INSERT INTO `double_field` (`field_id`, `decimal`) VALUES
(23, 1);

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
(5, NULL, 1),
(6, NULL, 2);

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
  `min_value` int(11) DEFAULT NULL COMMENT 'От',
  `max_value` int(11) DEFAULT NULL COMMENT 'Да',
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовое поле';

--
-- Дамп данных таблицы `integer_field`
--

INSERT INTO `integer_field` (`field_id`, `min_value`, `max_value`) VALUES
(28, 0, 999),
(29, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `list_field`
--

INSERT INTO `list_field` (`field_id`, `list_id`, `is_multiple_select`) VALUES
(33, 4, 1),
(34, 3, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Производители' AUTO_INCREMENT=12 ;

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
(8, 8, 1, 8, 1, 1, 'disc', 'Диски', '', ''),
(9, 8, 2, 3, 2, 1, 'AEZ', 'AEZ', 'AEZ', ''),
(10, 8, 4, 5, 2, 1, 'DEZENT', 'DEZENT', '', ''),
(11, 8, 6, 7, 2, 1, 'KK', 'K&K', '', '');

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

--
-- Дамп данных таблицы `price_field`
--

INSERT INTO `price_field` (`field_id`, `max_value`) VALUES
(2, NULL),
(6, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица продуктов магазина' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `status`, `name`, `alias`, `title`, `keywords`, `description`) VALUES
(1, 1, 'Шины', 'tires', 'Шины', '', ''),
(2, 1, 'Диски', 'disk', 'Диски', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `product_field`
--

INSERT INTO `product_field` (`id`, `position`, `product_id`, `field_type`, `name`, `alias`, `is_mandatory`, `is_filter`, `is_column_table`, `unit_name`, `hint`, `is_editing_table_admin`, `is_column_table_admin`) VALUES
(1, 0, 1, 2, 'Наименование', 'name', 1, 0, 1, '', 'Наименование шины', 1, 1),
(2, 1, 1, 3, 'Цена', 'price', 1, 1, 1, 'р.', '', 1, 1),
(5, 0, 2, 2, 'Наименование', 'name', 1, 1, 1, '', '', 1, 1),
(6, 1, 2, 3, 'Цена', 'price', 1, 1, 1, '', '', 1, 1),
(21, 5, 2, 11, 'Шипы', 'spikes', 0, 1, 1, '', '', 1, 1),
(23, 2, 2, 10, 'Вылет', 'boom', 0, 0, 1, '', '', 1, 1),
(28, 2, 1, 1, 'Количество', 'count', 0, 0, 1, '', 'шт.', 1, 1),
(29, 3, 2, 1, 'Количество', 'count', 0, 0, 1, 'шт.', '', 1, 1),
(32, 5, 1, 11, 'Шипы', 'logTest', 0, 1, 1, '', 'Да/Нет', 0, 0),
(33, 6, 1, 5, 'Высота профиля', 'listTest', 0, 1, 1, '', '', 0, 1),
(34, 4, 2, 5, 'Диаметр', 'diameter', 0, 0, 1, '', '', 0, 0);

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

--
-- Дамп данных таблицы `record_category`
--

INSERT INTO `record_category` (`product_id`, `record_id`, `category_id`) VALUES
(2, 7, 6),
(2, 2, 5),
(2, 22, 6),
(2, 4, 7),
(2, 3, 6),
(2, 23, 7),
(2, 24, 6),
(2, 1, 6);

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

--
-- Дамп данных таблицы `record_datetime`
--

INSERT INTO `record_datetime` (`product_id`, `record_id`, `datetime`) VALUES
(1, 1, '2012-08-01 00:00:00'),
(1, 1, '2012-08-02 00:00:00'),
(1, 2, '2012-08-02 00:00:00'),
(2, 3, '2012-08-17 00:00:00'),
(2, 3, '2012-08-18 00:00:00'),
(2, 3, '2012-08-19 00:00:00'),
(2, 4, '2012-08-31 00:00:00'),
(2, 22, '2012-08-01 00:00:00'),
(2, 22, '2012-08-04 00:00:00'),
(2, 22, '2012-08-30 00:00:00'),
(2, 22, '2012-08-31 00:00:00');

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

--
-- Дамп данных таблицы `record_list`
--

INSERT INTO `record_list` (`product_id`, `record_id`, `list_item_id`) VALUES
(1, 24, 38),
(1, 1, 35),
(1, 2, 35),
(1, 2, 37),
(1, 5, 35),
(1, 5, 41);

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

--
-- Дамп данных таблицы `record_manufacturer`
--

INSERT INTO `record_manufacturer` (`product_id`, `record_id`, `manufacturer_id`) VALUES
(2, 2, 10),
(2, 22, 9),
(2, 4, 10),
(2, 3, 10),
(2, 23, 10),
(2, 24, 10);

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
(1, 0, 255),
(5, 0, 255);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Дамп данных таблицы `tires`
--

INSERT INTO `tires` (`id`, `alias`, `title`, `keywords`, `description`, `name`, `price`, `count`, `logTest`) VALUES
(0, 'asdasd', '', '', '', 'Bridgestone Ice Cruiser 7000 235/70 R16 T', '7202.00', 2, 0),
(2, '', '', '', '', 'Bridgestone IC7000 185/65 R15 88T', '3690.00', 23, 0),
(3, '', '', '', '', 'Nokian Hakkapeliitta R SUV', '324.00', NULL, 0),
(4, '', NULL, NULL, NULL, 'шина 2', '4345.00', 4, 0),
(5, '', '', '', '', 'шина 4', '345.00', NULL, 0),
(6, '', NULL, NULL, NULL, 'шина 45', '345354.00', NULL, 0),
(7, '', NULL, NULL, NULL, 'шина 433', '324.00', NULL, 0),
(8, '', NULL, NULL, NULL, 'шина 4', '324.00', 5, 0),
(9, '', NULL, NULL, NULL, '213', '345.00', NULL, 0),
(10, '', NULL, NULL, NULL, 'шина 4343', '3434.00', NULL, 0),
(11, '', NULL, NULL, NULL, '3234', '234234.00', NULL, 0),
(12, '', NULL, NULL, NULL, 'dsfsdf', '434.00', NULL, 0),
(13, '', NULL, NULL, NULL, '234234', '234234.00', NULL, 0),
(14, '', NULL, NULL, NULL, 'шина 4324234', '234234.00', NULL, 0),
(15, '', NULL, NULL, NULL, 'шина 4', '234234.00', NULL, 0),
(16, '', NULL, NULL, NULL, 'шина 4', '234234.00', NULL, 0),
(17, '', NULL, NULL, NULL, 'шина 445', '9655.00', NULL, 0),
(18, '', NULL, NULL, NULL, 'шина 4555', '555.00', NULL, 0),
(19, '', NULL, NULL, NULL, 'шина 4324234', '234234.00', NULL, 0),
(20, '', NULL, NULL, NULL, 'шина 4', '234234.00', NULL, 0),
(21, '', NULL, NULL, NULL, 'шина 4', '234234.00', NULL, NULL),
(22, '', NULL, NULL, NULL, 'шина 445', '9655.00', NULL, NULL),
(23, '', NULL, NULL, NULL, 'шина 4555', '556.00', NULL, NULL),
(24, NULL, NULL, NULL, NULL, 'test 1', '111.00', 222, 0);

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
  ADD CONSTRAINT `field_tab_ibfk_2` FOREIGN KEY (`tab_id`) REFERENCES `tab` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `field_tab_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `product_field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
