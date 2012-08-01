<?php

class UsersController extends Controller
{

	public function actions(){
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
			),
		);
	}

	public function accessRules()
	{
		$rules = array_merge(
					parent::accessRules(),
					array(
						array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
								'actions'   =>  array('admin','delete'),
								'roles'     =>  array('Administrator')
						),
						array(  'deny',  // deny all users
								'users'=>array('*'),
						)
					)
				);

		return $rules;
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id)
	{
		$model = $this->loadModel($id);

		$model->scenario = "edit";
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes = $_POST['Users'];
			if($model->validate() && $model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}

	public function actionPasswordEdit($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->setScenario('passwordedit');
			$model->attributes=$_POST['Users'];
			if ( $model->validate() ){
				$model->md5Password();
				if($model->save(false))
					$this->redirect(array('view','id'=>$model->ID));
			}

		}

		$this->render('passwordedit',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria();

		$count = Users::model()->count($criteria);

		$pages=new CPagination($count);
		// элементов на страницу
		$pages->pageSize=10;
		$pages->applyLimit($criteria);

		$Users = Users::model()->findAll($criteria);

		$this->render('index', array(
			'Users' => $Users,
			'pages' => $pages
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
