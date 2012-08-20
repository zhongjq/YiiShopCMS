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
                '/admin/categories' => 'admin/category/index',
                '/admin/category/<action:(add|edit|delete)>/<id>' => 'admin/category/<action>',
                '/category/<alias>' => '/category/view',
                // Производители
                '/admin/manufacturers' => 'admin/manufacturer/index',
                '/admin/manufacturer/<action:(add|edit|delete)>/<id>' => 'admin/manufacturer/<action>',
                '/manufacturer/<alias>' => '/manufacturer/view',
				// Пользователи
		        '/admin/user/<action:(edit|view|delete|passwordedit)>/<id>' => 'admin/users/<action>',
                // Продукты
		        '/admin/products' => 'admin/product/index',                
                '/admin/product/<action:(edit|view|delete)>/<id:\d+>'=>  'admin/product/<action>',
                    // записи
                '/admin/product/view/<productId:\d+>/add' => 'admin/product/add',		        
                '/admin/product/view/<productId:\d+>/record/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/product/<action>record',
                    // поля
                '/admin/product/edit/<productId:\d+>/fields' => 'admin/product/fields',
		        '/admin/product/edit/<productId:\d+>/fields/add' => 'admin/product/addfield',
		        '/admin/product/edit/<productId:\d+>/fields/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/product/<action>field',
                // Списки
		        '/admin/products/lists' => 'admin/product/lists',
                '/admin/products/lists/add' => 'admin/product/addlist',
		        '/admin/products/list/<action:(edit|delete)>/<ListID:\d+>' => 'admin/product/<action>list',
                '/admin/products/list/<ListID:\d+>/items' => 'admin/product/itemslist',
                '/admin/products/list/<ListID:\d+>/items/add' => 'admin/product/additems',
                '/admin/products/list/<ListID:\d+>/item/<action:(edit|delete)>/<ItemID:\d+>' => 'admin/product/<action>item',

		         // своё правило для URL вида '/Производитель/Модель'
		         array(
			         'class' => 'application.components.UrlRule',
			         'connectionID' => 'db',
		         ),

	        );
