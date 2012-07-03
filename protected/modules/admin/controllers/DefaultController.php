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

}