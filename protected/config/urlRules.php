<?php

return array(
		         '/'=>'site/index',

		         //// ПОЛЬЗОВАТЕЛЬ
				// просмотр пользователя
		        '/profile/' => 'user/profile',
		        '/profile/edit/' => 'user/profileedit',
		        '/user/<id>' => 'user/view',
		        '/user/<action:(edit|delete|passwordedit)>/<id>' => 'user/<action>',

				// стандартное правило для обработки '/login' как 'site/login' и т.д.
				"<action:(login|logout|registration|signup)>" => 'site/<action>',
				// подтверждение регистрации
				'/confirmation/<code>' => 'site/confirmation',


                /* АДМИНИСТРАТИРОВАНИЕ */
                "/admin/<action:(login|logout)>" => "admin/default/<action>",
                // Категории
                '/admin/category/<action:(add|edit|delete)>/<id>'   =>  'admin/category/<action>',
                // Производители
                '/admin/manufacturer/<action:(add|edit|delete)>/<ManufacturerID>'   =>  'admin/manufacturers/<action>',

				// Пользователи
		        '/admin/user/<action:(edit|view|delete|passwordedit)>/<id>' =>  'admin/users/<action>',
                // Продукты
		        '/admin/products' => 'admin/product/index',

                '/admin/product/<action:(edit|view|delete)>/<ProductID:\d+>'=>  'admin/product/<action>',
		        '/admin/product/view/<ProductID:\d+>/add'                   =>  'admin/product/add',
                '/admin/product/view/<ProductID:\d+>/record/<action:(edit|delete)>/<RecordID:\d+>'  =>  'admin/product/<action>record',

                '/admin/product/edit/<ProductID:\d+>/fields'                       =>  'admin/product/fields',
		        '/admin/product/edit/<ProductID:\d+>/fields/add'                   =>  'admin/product/addfield',
		        '/admin/product/edit/<ProductID:\d+>/fields/<action:(edit|delete)>/<FieldID:\d+>'  =>  'admin/product/<action>field',

		        '/admin/products/lists'                                     =>  'admin/product/lists',
                '/admin/products/lists/add'                                 =>  'admin/product/addlist',
		        '/admin/products/list/<action:(edit|delete)>/<ListID:\d+>'  =>  'admin/product/<action>list',
                '/admin/products/list/<ListID:\d+>/items'                   =>  'admin/product/itemslist',
                '/admin/products/list/<ListID:\d+>/items/add'               =>  'admin/product/additems',
                '/admin/products/list/<ListID:\d+>/item/<action:(edit|delete)>/<ItemID:\d+>'  =>  'admin/product/<action>item',

                // Категории
		        '/category/<Alias>' => '/categories/view/',

		         // своё правило для URL вида '/Производитель/Модель'
		         array(
			         'class' => 'application.components.UrlRule',
			         'connectionID' => 'db',
		         ),

	        );
