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
		        '/admin/constructor' => 'admin/constructor/index',
                '/admin/constructor/<action:(edit|view|delete)>/<id:\d+>'=>  'admin/constructor/<action>',
				// Поля
                '/admin/constructor/<id:\d+>/fields' => 'admin/constructor/fields',
                '/admin/constructor/<id:\d+>/sorting' => 'admin/constructor/sorting',
		        '/admin/constructor/<id:\d+>/fields/add' => 'admin/constructor/addfield',
		        '/admin/constructor/<productId:\d+>/field/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/constructor/<action>field',
				// Форма
                '/admin/constructor/<id:\d+>/form' => 'admin/constructor/form',
                '/admin/constructor/<id:\d+>/addtab' => 'admin/constructor/addtab',
                '/admin/constructor/<id:\d+>/form/savepositiontabs' => 'admin/constructor/savePositionTabs',
                '/admin/constructor/<id:\d+>/form/savepositionfield' => 'admin/constructor/savePositionField',
                '/admin/constructor/<id:\d+>/form/savepositionfields' => 'admin/constructor/savePositionFields',
		        '/admin/constructor/<productId:\d+>/form/tab<action:(edit|delete)>/<tabId:\d+>' => 'admin/constructor/<action>tab',
		        '/admin/constructor/<productId:\d+>/field/<action:(edit|delete)>/<fieldId:\d+>' => 'admin/constructor/<action>field',
                

                // записи
                '/admin/product/<id:\d+>' => 'admin/product/view',
                '/admin/product/<id:\d+>/add' => 'admin/product/add',
                '/admin/product/<productId:\d+>/record/<action:(edit|delete)>/<recordId:\d+>' => 'admin/product/<action>record',

				// Списки
                '/admin/lists/add' => 'admin/lists/add',
		        '/admin/list/<action:(edit|delete)>/<id:\d+>' => 'admin/lists/<action>list',
                '/admin/list/<id:\d+>/items' => 'admin/lists/items',
                '/admin/list/<id:\d+>/items/add' => 'admin/lists/additems',
                '/admin/list/<listId:\d+>/item/<action:(edit|delete)>/<itemId:\d+>' => 'admin/lists/<action>item',

		         // своё правило для URL вида '/Производитель/Модель'
//		         array(
//			         'class' => 'application.components.UrlRule',
//			         'connectionID' => 'db',
//		         ),

	        );
