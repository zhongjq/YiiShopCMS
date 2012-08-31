<?php

return array(
	'name'				=>  'Большой Магазин',
	'defaultController'	=>  'site',
	'sourceLanguage'    =>  'en',
	'language'          =>  'ru',
	'theme'             =>  'classic',

	'params'=>array(
		'keywords'		=>	array(),
		'description'	=>	'',
	 ),

	// подгружаем модели к классы
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.widgets.*',
	),

	// Сжатие
	//'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
	//'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),

	// Модули
	'modules'=>array(
        'gii'=>array(
            'class'         =>  'system.gii.GiiModule',
            'password'      =>  '1',
            'ipFilters'     =>  array("192.168.56.1","127.0.0.1",),
            'newFileMode'   =>  0666,
            'newDirMode'    =>  0777,
        ),
    	'admin' => array(
			'layout'=>'application.modules.admin.views.layouts.main',
		),
    ),
	'preload'=>array('log'),
	// Компоненты
	'components'=>array(

		'clientScript'=>array(
            'scriptMap'=>array(
                'jquery.js' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js',
                //'jquery-ui.js' => 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js',
				//'jquery.ajaxqueue.js'	=> false,
				//'jquery.metadata.js' => false,
				//'jquery.yiilistview.js' => false,
				//'jquery.ba-bbq.js' => false,
				'styles.css' => false,
            ),
            //'enableJavaScript'=>false,    // Эта опция отключает любую генерацию javascript'а фреймворком
        ),
        
		//'cache'=>array(
		//	'class'=>'system.caching.CDbCache',
		//),
		// База
		'db'=>array(
			//'connectionString' => 'mysql:host=localhost;dbname=enchikiben_fbfde',
            'connectionString' => 'mysql:host=mysql0.db.koding.com;dbname=enchikiben_fbfde',
			'emulatePrepare' => true,
			'username' => 'enchikiben_fbfde',
			'password' => '754089db',
			'charset' => 'utf8',
			'tablePrefix' => '',
            'schemaCachingDuration'=>3600,
			// включаем профайлер
			'enableProfiling' => true,
			// показываем значения параметров
			'enableParamLogging' => true,
		),

		// пользователи
		'session' => array(
			'autoStart'		=> true,
            //'cookieParams'	=> array('domain' => '.'.$_SERVER['SERVER_NAME'] ),
        ),

        'user' => array(
			'class' => 'WebUser',
			'allowAutoLogin'	=> true,
			'allowAutoLogin'	=> true,
            //'identityCookie'	=> array('domain' => '.'.$_SERVER['SERVER_NAME']  ),
			'loginUrl'			=> array('login'),
        ),

 		'authManager'=>array(
			'class' => 'PhpAuthManager',
			'defaultRoles' => array('guest'),
        ),

		// адресация
        'urlManager'=>array(
        	'urlFormat' => 'path',
			'showScriptName' => false,
            // тут правим если запускаем из подпапки
			'baseUrl' => 'http://'.$_SERVER['SERVER_NAME']."/yiishop",
         	'rules' => require 'rules.php',

			'urlSuffix' => '.html',
        ),

        'errorHandler'=>array(
			'errorAction'=>'site/error',
        ),

		'preload'=>array('log'),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					//выводим лог внизу страницы
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace, profile, info',
				),

				array(
					'class' => 'CWebLogRoute',
					'categories' => 'application',
					'showInFireBug' => true
				),

			),
		),

	),

);

