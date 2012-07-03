<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='/layouts/main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $FirstMenu	=	array();
	public $SecondMenu	=	array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs	=	array();


	public function accessRules()
	{
		return array(
			array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
					'roles'     =>  array('Administrator')
			),
			array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
					'actions'   =>  array('error'),
					'users'     =>  array('*'),
			),
			array(  'deny',     // deny all users
					'users'     =>  array('*'),
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

}