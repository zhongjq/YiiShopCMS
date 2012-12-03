<?php

// для production режима эту строку удалите
defined('YII_DEBUG') or define('YII_DEBUG',true);

define('DIR', substr(dirname(__FILE__), strlen($_SERVER['DOCUMENT_ROOT'])) );

// include setting
include dirname(__FILE__).'/config.php';

// include Yii bootstrap file
require_once(dirname(__FILE__).'/yii/framework/yii.php');

// подключаем конфигурацию
$config=dirname(__FILE__).'/protected/config/main.php';

// create a Web application instance and run
Yii::createWebApplication($config)->run();