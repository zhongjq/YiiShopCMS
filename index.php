<?php
ini_set('zend.ze1_compatibility_mode', 0);

// для production режима эту строку удалите
defined('YII_DEBUG') or define('YII_DEBUG',true);

// include Yii bootstrap file
require_once(dirname(__FILE__).'/yii/framework/yiilite.php');

// подключаем конфигурацию
$config=dirname(__FILE__).'/protected/config/main.php';

// create a Web application instance and run
Yii::createWebApplication($config)->run();