<?php

class DefaultController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionError(){
		if($error=Yii::app()->errorHandler->error)
			$this->render('error', $error);
	}
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

		$model = new Users('login');

		if (!Yii::app()->user->isGuest) {
			throw new CHttpException(403,'Недостаточно прав!');
		} else {
			if (!empty($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				if($model->validate()) {
					$this->redirect(  Yii::app()->user->returnUrl );
				}
			}

		}

		$this->render('login', array('model'=>$model) );
	}

}