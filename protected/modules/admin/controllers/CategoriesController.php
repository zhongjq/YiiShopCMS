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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'root,lft';
        $Categories	= new CActiveDataProvider('Categories',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));		
		
		$this->render('index', array(
			'Categories' => $Categories
		));
	}	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$Category  =   new Categories('add');

    	if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "CategoryForm" ){
			echo CActiveForm::validate($Category);
			Yii::app()->end();
		}

		if(isset($_POST['Categories']))
		{
			$Category->attributes = $_POST['Categories'];
			if($Category->validate()){
				$Category->Alias = Controller::translit($Category->Name);

				if ($Category->ParentID == 0){
					$Category->saveNode();
					$this->redirect(array('/admin/categories'));
				} else {
					$Root = Categories::model()->findByPk($Category->ParentID);

					if ( $Root ){
						$Category->appendTo($Root);
						$this->redirect(array('/admin/categories'));
					}
				}

			}
		}
        
        $Form = new CForm( $Category->getArrayCForm(), $Category );        
        
		$this->render('add',array(
			'Form'=>$Form,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($CategoryID)
	{
		$Category = $this->loadModel($CategoryID);
        $Category->setScenario('edit');
		if ( $Category->parent()->find() )
			$Category->ParentID = $model->parent()->find()->ID;

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "CategoryForm" ){
			echo CActiveForm::validate($Category);
			Yii::app()->end();
		}

		if(isset($_POST['Categories']))
		{
			$Category->attributes = $_POST['Categories'];
			if($Category->validate()){
				$Category->Alias = Controller::translit($model->Name);

				if ($Category->ParentID == 0 && !$Category->isRoot()){
					$Category->moveAsRoot();

				} else {
					$Root = Categories::model()->findByPk($Category->ParentID);

					if ( $Root ){
						$model->moveAsFirst($Root);
					}
				}
				$Category->saveNode();
				$this->redirect(array('/admin/categories'));
			}
		}
        
        $Form = new CForm( $Category->getArrayCForm(), $Category );   
        
		$this->render('edit',array(
			'Form'=>$Form,
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

}
