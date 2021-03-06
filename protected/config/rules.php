<?php

return array(
		        '/<action:(index|test)>'=>'site/<action>',

                '/cart'=>'cart/index',
                '/cart/<product>/<id>/add'=>'cart/add',


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

                '/manufacturers' => 'manufacturer/index',


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
                '/admin/users' => 'admin/users/index',
                '/admin/user/add' => 'admin/users/add',
		        '/admin/user/<action:(edit|view|delete|passwordedit)>/<id>' => 'admin/users/<action>',
                // Продукты
		        '/admin/constructor' => 'admin/constructor/index',
                '/admin/constructor/<action:(edit|view|delete)>/<id:\d+>'=>  'admin/constructor/<action>',
				// Поля
                '/admin/constructor/<id:\d+>/<action:(fields|sorting)>' => 'admin/constructor/<action>',
		        '/admin/constructor/<id:\d+>/fields/add' => 'admin/constructor/addfield',
		        '/admin/constructor/<productId:\d+>/field/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/constructor/<action>field',
				// Форма
                '/admin/constructor/<id:\d+>/<action:(form|addtab)>' => 'admin/constructor/<action>',
                '/admin/constructor/<id:\d+>/form/<action:(savePositionTabs|savePositionField|savePositionFields)>' => 'admin/constructor/<action>',
		        '/admin/constructor/<productId:\d+>/form/tab<action:(edit|delete)>/<tabId:\d+>' => 'admin/constructor/<action>tab',
		        '/admin/constructor/<productId:\d+>/field/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/constructor/<action>field',


                // записи
                '/admin/product/<id:\d+>' => 'admin/product/view',
                'admin/product/<id:\d+>/<action:(add|export|import)>' => 'admin/product/<action>',
                '/admin/product/<productId:\d+>/record/' => 'admin/product/<action>record',

				// Списки
                '/admin/lists' => 'admin/lists',
                '/admin/lists/add' => 'admin/lists/add',
		        '/admin/list/<action:(edit|delete)>/<id:\d+>' => 'admin/lists/<action>list',
                '/admin/list/<id:\d+>/items' => 'admin/lists/items',
                '/admin/list/<id:\d+>/items/add' => 'admin/lists/additems',
                '/admin/list/<listId:\d+>/item/<action:(edit|delete)>/<itemId:\d+>' => 'admin/lists/<action>item',


		         // своё правило для URL вида '/Производитель/Модель'
		         array('class' => 'application.components.UrlRule','connectionID' => 'db'),

	        );
