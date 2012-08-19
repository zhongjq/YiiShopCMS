<?php

class CategoryController extends Controller
{

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

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'root,lft';
        $categories	= new CActiveDataProvider('Category',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('index', array(
			'categories' => $categories
		));
	}

	public function actionAdd()
	{
		$category = new Category('add');

		$this->performAjaxValidation($category);

		if(isset($_POST['Category']))
		{
			$category->attributes = $_POST['Category'];
			if($category->validate()){

				if ($category->parentId == 0){
					$category->saveNode();
					$this->redirect(array('/admin/category'));
				} else {
					$root = Category::model()->findByPk($category->parentId);

					if ( $root ){
						$category->appendTo($root);
						$this->redirect(array('/admin/category'));
					}
				}

			}
		}

		$form = new CForm( $category->getArrayCForm(), $category );

		$this->render('add',array(
			'form'=>$form,
		));
	}

	public function actionEdit($id)
	{
		$category = $this->loadModel($id);
        $category->setScenario('edit');

		if ( $category->parent()->find() )
			$category->parentId = $category->parent()->find()->id;

        $this->performAjaxValidation($category);

		if(isset($_POST['Category']))
		{
			$category->attributes = $_POST['Category'];
			if($category->validate()){

				if ($category->parentId == 0 && !$category->isRoot()){
					$category->moveAsRoot();

				} else {
					$root = Category::model()->findByPk($category->parentId);

					if ( $root ){
						$category->moveAsFirst($root);
					}
				}
				$category->saveNode();
				$this->redirect(array('/admin/category'));
			}
		}

        $form = new CForm( $category->getArrayCForm(), $category );

		$this->render('edit',array(
			'form'=>$form,
		));
	}


	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->deleteNode();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id)
	{
		$model=Category::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($category)
	{
        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "categoryForm" ){
    		echo CActiveForm::validate($category);
			Yii::app()->end();
		}
	}

}
