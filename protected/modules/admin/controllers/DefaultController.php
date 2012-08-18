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

	public function actionError()
	{
		$error = Yii::app()->errorHandler->error;
		if($error)
			$this->render('error', $error);
	}

	public function actionLogin()
	{

		$User = new Users('login');

		if (!Yii::app()->user->isGuest) {
			throw new CHttpException(403,'Недостаточно прав!');
		} else {
			if (!empty($_POST['Users'])) {
				$User->attributes = $_POST['Users'];
				if($User->validate()) {
					$this->redirect( $this->createUrl('/admin') );
				}
			}

		}

		$Form = new CForm( $User->getArrayLoginCForm(), $User );

		$this->render('login', array('Form'=>$Form) );
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect( $this->createUrl('/admin') );
		$this->redirect(Yii::app()->homeUrl);
	}
}