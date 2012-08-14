<?php

class CategoriesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
	public function actionAdd()
	{
		$model=new Categories;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Categories']))
		{
			$model->attributes = $_POST['Categories'];
			if($model->validate()){
				$model->Alias = Controller::translit($model->Name);

				if ($model->Parent == 0){
					$model->saveNode();
					$this->redirect(array('view','id'=>$model->ID));
				} else {
					$Root = Categories::model()->findByPk($model->Parent);

					if ( $Root ){
						$model->appendTo($Root);
						$this->redirect(array('view','id'=>$model->ID));
					}
				}

			}

		}

		$this->render('add',array(
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
		$model=$this->loadModel($id);

		if ( $model->parent()->find() )
			$model->Parent = $model->parent()->find()->ID;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Categories']))
		{
			$model->attributes = $_POST['Categories'];
			if($model->validate()){
				$model->Alias = Controller::translit($model->Name);

				if ($model->Parent == 0 && !$model->isRoot()){
					$model->moveAsRoot();

				} else {
					$Root = Categories::model()->findByPk($model->Parent);

					if ( $Root ){
						$model->moveAsFirst($Root);
					}
				}
				$model->saveNode();
				$this->redirect(array('view','id'=>$model->ID));
			}
		}

		$this->render('update',array(
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
		$criteria->order = 'root,lft';
		$count = Categories::model()->count($criteria);

		$pages=new CPagination($count);

		$pages->pageSize=10;
		$pages->applyLimit($criteria);

		$Categories = Categories::model()->findAll($criteria);

		$this->render('index', array(
			'Categories' => $Categories,
			'pages' => $pages
		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Categories('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Categories']))
			$model->attributes=$_GET['Categories'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Categories::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}