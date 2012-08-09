<?php

class AdminModule extends CWebModule
{
	public function init()
	{

		$this->setImport(array(
			'application.models.*',
			'application.components.*',
		
			'admin.models.*',
			'admin.components.*',
		));
		
		Yii::app()->setComponents(array(
            'errorHandler'=>array(
				'errorAction'=>'/admin/default/error',
			),
			'user' => array(
				'class'     => 'CWebUser',
				'loginUrl'  => Yii::app()->createUrl('admin/default/login'),
			)
		));

	}


	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			//if( Yii::app()->user->isGuest ) Yii::app()->user->loginRequired();

			return true;
		}
		else
			return false;
	}

}
