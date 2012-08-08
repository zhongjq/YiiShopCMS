-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 09 2012 г., 00:34
-- Версия сервера: 5.1.63
-- Версия PHP: 5.3.3-7+squeeze13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `yiishop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `Level` smallint(5) unsigned NOT NULL,
  `Status` int(1) NOT NULL COMMENT 'Тип категории',
  `Alias` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Категории' AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `Categories`
--

INSERT INTO `Categories` (`ID`, `root`, `lft`, `rgt`, `Level`, `Status`, `Alias`, `Name`, `Description`) VALUES
(1, 1, 1, 8, 1, 1, 'shiny', 'Шины', 'Шины'),
(2, 2, 1, 2, 1, 1, 'diski', 'Диски', 'Диски'),
(3, 1, 2, 3, 2, 1, 'letnie', 'Летние', 'Летние'),
(4, 1, 4, 5, 2, 1, 'zimnie', 'Зимние', 'Зимние'),
(5, 1, 6, 7, 2, 1, 'vsesezonnye', 'Всесезонные', 'Всесезонные');

-- --------------------------------------------------------

--
-- Структура таблицы `disk`
--

CREATE TABLE IF NOT EXISTS `disk` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Alias` varchar(255) DEFAULT NULL,
  `Title` text,
  `Keywords` text,
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `disk`
--


-- --------------------------------------------------------

--
-- Структура таблицы `IntegerFields`
--

CREATE TABLE IF NOT EXISTS `IntegerFields` (
  `FieldID` int(11) NOT NULL,
  `MinValue` int(11) NOT NULL COMMENT 'От',
  `MaxValue` int(11) NOT NULL COMMENT 'Да',
  PRIMARY KEY (`FieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Числовые поля';

--
-- Дамп данных таблицы `IntegerFields`
--

INSERT INTO `IntegerFields` (`FieldID`, `MinValue`, `MaxValue`) VALUES
(2, 0, 100);

-- --------------------------------------------------------

--
-- Структура таблицы `Lists`
--

CREATE TABLE IF NOT EXISTS `Lists` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `Lists`
--


-- --------------------------------------------------------

--
-- Структура таблицы `ListsItems`
--

CREATE TABLE IF NOT EXISTS `ListsItems` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ListID` int(11) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  `Priority` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ListID` (`ListID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Списки' AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `ListsItems`
--


-- --------------------------------------------------------

--
-- Структура таблицы `PriceFields`
--

CREATE TABLE IF NOT EXISTS `PriceFields` (
  `FieldID` int(11) NOT NULL,
  `MaxValue` int(11) NOT NULL COMMENT 'Да',
  PRIMARY KEY (`FieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ценовые поля';

--
-- Дамп данных таблицы `PriceFields`
--

INSERT INTO `PriceFields` (`FieldID`, `MaxValue`) VALUES
(4, 10000);

-- --------------------------------------------------------

--
-- Структура таблицы `Products`
--

CREATE TABLE IF NOT EXISTS `Products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  `Name` varchar(255) NOT NULL,
  `Alias` varchar(255) NOT NULL,
  `Title` text NOT NULL,
  `Keywords` text NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Status` (`Status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица продуктов магазина' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `Products`
--

INSERT INTO `Products` (`ID`, `Status`, `Name`, `Alias`, `Title`, `Keywords`, `Description`) VALUES
(1, 1, 'Шины', 'tires', '', '', ''),
(2, 1, 'Диски', 'disk', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ProductsFields`
--

CREATE TABLE IF NOT EXISTS `ProductsFields` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Position` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `FieldType` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Alias` varchar(50) NOT NULL,
  `IsMandatory` tinyint(1) NOT NULL DEFAULT '0',
  `IsFilter` tinyint(1) NOT NULL DEFAULT '0',
  `IsColumnTable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'используется в заголовке таблицы',
  `UnitName` varchar(255) NOT NULL COMMENT 'Единицы измерения',
  `Hint` varchar(255) NOT NULL COMMENT 'Подсказка',
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `ProductsFields`
--

INSERT INTO `ProductsFields` (`ID`, `Position`, `ProductID`, `FieldType`, `Name`, `Alias`, `IsMandatory`, `IsFilter`, `IsColumnTable`, `UnitName`, `Hint`) VALUES
(1, 0, 1, 2, 'Наименование товара', 'Name', 1, 1, 1, '', ''),
(2, 0, 1, 1, 'Количество', 'Col', 0, 1, 1, '', ''),
(3, 0, 1, 4, 'Описание', 'decs', 0, 0, 1, '', ''),
(4, 0, 1, 3, 'Цена', 'Price', 1, 1, 1, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `StringFields`
--

CREATE TABLE IF NOT EXISTS `StringFields` (
  `FieldID` int(11) NOT NULL,
  `MinLength` int(3) NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `MaxLength` int(3) NOT NULL DEFAULT '255' COMMENT 'Максимальная длинна',
  PRIMARY KEY (`FieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Строковые поля';

--
-- Дамп данных таблицы `StringFields`
--

INSERT INTO `StringFields` (`FieldID`, `MinLength`, `MaxLength`) VALUES
(1, 0, 255);

-- --------------------------------------------------------

--
-- Структура таблицы `TextFields`
--

CREATE TABLE IF NOT EXISTS `TextFields` (
  `FieldID` int(11) NOT NULL,
  `MinLength` int(11) NOT NULL DEFAULT '0' COMMENT 'Минимальная длинна',
  `MaxLength` int(11) NOT NULL DEFAULT '10000' COMMENT 'Максимальная длинна',
  `Rows` int(11) NOT NULL DEFAULT '5' COMMENT 'Строк',
  PRIMARY KEY (`FieldID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Текстовые поля';

--
-- Дамп данных таблицы `TextFields`
--

INSERT INTO `TextFields` (`FieldID`, `MinLength`, `MaxLength`, `Rows`) VALUES
(3, 0, 10000, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `tires`
--

CREATE TABLE IF NOT EXISTS `tires` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Alias` varchar(255) DEFAULT NULL,
  `Title` text,
  `Keywords` text,
  `Description` text,
  `Name` varchar(255) DEFAULT NULL,
  `Col` int(11) DEFAULT NULL,
  `decs` text,
  `Price` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `tires`
--

INSERT INTO `tires` (`ID`, `Alias`, `Title`, `Keywords`, `Description`, `Name`, `Col`, `decs`, `Price`) VALUES
(1, '', '', '', '', 'Шина 1', 3, '', '234.30');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Включен/Выключен',
  `RoleID` int(5) DEFAULT '1' COMMENT 'Номер роли',
  `RegistrationDateTime` datetime DEFAULT NULL COMMENT 'Дата и время регистрации',
  `ServiceID` int(5) DEFAULT '1' COMMENT 'Идентификатор сервиса (1 - локальный пользователь)',
  `ServiceUserID` varchar(255) DEFAULT NULL COMMENT 'Идентификатор пользователя в сервисе',
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица пользователей' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`ID`, `Status`, `RoleID`, `RegistrationDateTime`, `ServiceID`, `ServiceUserID`, `Email`, `Password`, `UserName`) VALUES
(1, 0, 2, '2012-06-30 20:17:00', 1, '', 'enchikiben@gmail.com', 'a37e9e0ada9d5eef566727a9a8ea36e8', NULL),
(2, 0, 1, '2012-07-04 00:41:25', 3, 'http://openid.yandex.ru/EnChikiben/', NULL, NULL, 'EnChikiben');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `IntegerFields`
--
ALTER TABLE `IntegerFields`
  ADD CONSTRAINT `IntegerFields_ibfk_1` FOREIGN KEY (`FieldID`) REFERENCES `ProductsFields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ListsItems`
--
ALTER TABLE `ListsItems`
  ADD CONSTRAINT `ListsItems_ibfk_1` FOREIGN KEY (`ListID`) REFERENCES `Lists` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `PriceFields`
--
ALTER TABLE `PriceFields`
  ADD CONSTRAINT `PriceFields_ibfk_1` FOREIGN KEY (`FieldID`) REFERENCES `ProductsFields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ProductsFields`
--
ALTER TABLE `ProductsFields`
  ADD CONSTRAINT `ProductsFields_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `StringFields`
--
ALTER TABLE `StringFields`
  ADD CONSTRAINT `StringFields_ibfk_1` FOREIGN KEY (`FieldID`) REFERENCES `ProductsFields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `TextFields`
--
ALTER TABLE `TextFields`
  ADD CONSTRAINT `TextFields_ibfk_1` FOREIGN KEY (`FieldID`) REFERENCES `ProductsFields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
