<?php

class DefaultController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl',
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
		$user = new User('login');

		if (!Yii::app()->user->isGuest) {
			throw new CHttpException(403,'Недостаточно прав!');
		} else {
			if (!empty($_POST['User'])) {
				$user->attributes = $_POST['User'];
				if($user->validate()) {
					$this->redirect( $this->createUrl('/admin') );
				}
			}

		}

		$form = new CForm( $user->getArrayLoginCForm(), $user );

		$this->render('login', array('form'=>$form) );
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect( $this->createUrl('/admin') );
		$this->redirect(Yii::app()->homeUrl);
	}
}