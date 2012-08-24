<?php

class ManufacturerController extends Controller
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
        $manufacturers	= new CActiveDataProvider('Manufacturer',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('index', array(
			'manufacturers' => $manufacturers
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$manufacturer = new Manufacturer('add');

    	$this->performAjaxValidation($manufacturer);

		if(isset($_POST['Manufacturer']))
		{
			$manufacturer->attributes = $_POST['Manufacturer'];
			$manufacturer->logoFile	= CUploadedFile::getInstance($manufacturer,'logo');
			if($manufacturer->validate()){
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					if ($manufacturer->parentId == 0){
						$manufacturer->saveNode();
						$transaction->commit();
						$this->redirect(array('/admin/manufacturers'));
					} else {
						$root = Manufacturer::model()->findByPk($manufacturer->parentId);

						if ( $root ){
							$manufacturer->appendTo($root);
							$transaction->commit();
							$this->redirect(array('/admin/manufacturers'));
						}
					}
				}
				catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
				{
					$transaction->rollBack();
				}
			}
		}

        $form = new CForm( $manufacturer->getArrayCForm(), $manufacturer );

		$this->render('add',array(
			'form'=>$form,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id)
	{
		$manufacturer = $this->loadModel($id);
        $manufacturer->setScenario('edit');
		if ( $manufacturer->parent()->find() )
			$manufacturer->parentId = $manufacturer->parent()->find()->id;
        $this->performAjaxValidation($manufacturer);

    	if(isset($_POST['Manufacturer']))
		{
			$manufacturer->oldLogoFile = $manufacturer->logo;
			$manufacturer->attributes = $_POST['Manufacturer'];
			$manufacturer->logoFile	= CUploadedFile::getInstance($manufacturer,'logo');
			if($manufacturer->validate()){
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					if ($manufacturer->parentId == 0 && !$manufacturer->isRoot()){
						$manufacturer->moveAsRoot();

					} else {
						$root = Manufacturer::model()->findByPk($manufacturer->parentId);

						if ( $root ){
							$manufacturer->moveAsFirst($root);
						}
					}
					$manufacturer->saveNode();
					$transaction->commit();
					$this->redirect(array('/admin/manufacturers'));
				}
				catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
				{
					$transaction->rollBack();
				}
			}
		}

        $form = new CForm( $manufacturer->getArrayCForm(), $manufacturer );

		$this->render('edit',array(
			'form'=>$form,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($ManufacturerID)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($ManufacturerID)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Manufacturer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    /**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($manufacturer)
	{
        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "manufacturerForm" ){
    		echo CActiveForm::validate($manufacturer);
			Yii::app()->end();
		}
	}

}
