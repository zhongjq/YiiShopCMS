<?php

class ProductController extends Controller
{
	public $layout='/layouts/main';

	public function actionIndex()
	{
    	$criteria = new CDbCriteria();
		$criteria->with = 'productFields';
        $products	= new CActiveDataProvider('Product',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('index', array(
			'products' => $products
		));
	}

	public function actionView($id)
	{
        $product = Product::model()->findByPk($id);
    	$model = $product->getRecordObject();
		//$model = DynamicActiveRecord::model($product->alias);

		if ( isset($_GET[$model->productName]) ){
			$model->attributes = $_GET[$model->productName];
		}

        if ( isset($_POST[$model->productName]) && is_array($_POST[$model->productName]) && !empty($_POST[$model->productName]) ){

            $records = $_POST[$model->productName];

            foreach( $records as $id => $data ){
                if ( is_numeric($id) ) {
                    $a = $model->findByPk($id);
                    if ( $a ){
                        $a->attributes = $data;
                        if (!$a->save()) {
                            print_r( $a->getErrors() );
                            die;
                        };
                    }
                }
            }

			$this->redirect($this->createUrl('/admin/product/view',array('id'=>$product->id)));
		}

		$this->render('records/view', array(
			'product' => $product,
			'record' => $model
		));
	}

	protected function performAjaxRecordValidation($record)
	{
		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "recordForm" )
		{
			echo CActiveForm::validate($record);
			Yii::app()->end();
		}
	}

	public function actionAdd($id)
	{

		$product = Product::getProductByPk($id);
		$model = DynamicActiveRecord::model($product->alias);

		$this->performAjaxRecordValidation($model);

		$form = $model->getMotelCForm();

		if(isset($_POST[$model->tableName()])) {

			$model->attributes = $_POST[$model->tableName()];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if($form->submitted() && $model->save()){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/view',array('id'=>$product->id)));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				Yii::app()->user->setFlash('error',$e->getFile()."<br/>". $e->getLine().": ". $e->getMessage());
				$transaction->rollBack();
			}
		}

		$this->render('records/add',array('product'=>$product,'form'=>$form));
	}

    public function actionEditRecord($productId,$recordId)
	{
		$product = Product::model()->findByPk($productId);

		$model = DynamicActiveRecord::model($product->alias);

        $model = $model->findByPk($recordId);

		$this->performAjaxRecordValidation($model);

		$form = $model->getMotelCForm();

		if(isset($_POST[$model->tableName()])) {

            $model->attributes = $_POST[$model->tableName()];

            $transaction = Yii::app()->db->beginTransaction();
			try
			{
				if($form->submitted() && $model->save()){

					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/view',array('id'=>$product->id)));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				Yii::app()->user->setFlash('error',$e->getFile()."<br/>". $e->getLine().": ". $e->getMessage());
				$transaction->rollBack();
			}
		}

		$this->render('records/edit',array('product'=>$product,'form'=>$form));
	}

    public function actionDeleteRecord($productId,$recordId)
    {
		$product = Product::model()->findByPk($productId);
		$record = $product->getRecordObject('delete');
        $record = $record->findByPk($recordId);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $record->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/product/view',array('id'=>$product->id)));
			}
		}
		catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
		{
			$transaction->rollBack();
		}
	}

	protected function performAjaxValidation($model)
	{
		if(Yii::app()->request->isAjaxRequest )
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionCreate()
	{

		$product = new Product('create');

		$this->performAjaxValidation($product);

		$form = $product->getMotelCForm();

		if(isset($_POST['Product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$product->attributes = $_POST['Product'];
				if( $product->save() ){
					Yii::app()->db->createCommand()->createTable($product->alias, array(
						'id' => 'pk',
						'alias' => 'varchar(255)',
						'title' => 'text',
						'keywords' => 'text',
						'description' => 'text',
					), 'ENGINE=InnoDB');

					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('create', array('form'=>$form) );
	}

	public function actionDelete($id)
	{
		Product::model()->findByPk($id)->delete();
		$this->redirect(array('/admin/product'));
	}

	public function actionEdit($id)
	{
		$product = Product::model()->findByPk($id);

		$this->performAjaxValidation($product);

		$form = $product->getMotelCForm();

		if(isset($_POST['Product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$product->attributes = $_POST['Product'];
				if( $product->save() ){
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('edit', array('form'=>$form,'product' => $product) );
	}


	public function actionFields($id)
	{
		$product = Product::model()->findByPk($id);

        $criteria=new CDbCriteria;
    	$criteria->compare('product_id',$id);
        $fields = new CActiveDataProvider('ProductField',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('fields/index', array(
			'product'   => $product,
            'fields'    => $fields
		));
	}

	public function actionAddField($id)
	{
		$product = Product::model()->findByPk($id);
		$productField = new ProductField('add');
		$productField->product_id = $product->id;

		$arProductFieldForm = $productField->getMotelArrayCForm();

		$class = null;
		$fieldType = null;
		$className = null;
		if ( isset($_POST['ProductField']['field_type']) )
			$fieldType = $_POST['ProductField']['field_type'];

		if ( $fieldType > 0 && isset(TypeField::$Fields[$fieldType]['class']) ){
			$className = TypeField::$Fields[$fieldType]['class'];
			$class = $productField->CreateField($fieldType);
			$arProductFieldForm['elements'][$className] = $class->getElementsMotelCForm();
			$productField->moredata = $class;
		}

		$form = new CForm($arProductFieldForm);
		$form['productField']->model = $productField;
		if( $fieldType > 0 && $class ) $form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($productField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$className];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $form->submitted() && $productField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$product->id)));
				} else {
					throw new CException("Error save");
				}

			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		if( Yii::app()->request->isAjaxRequest && $fieldType > 0 ){
			$form = $form->render();
			$sc = '';
			Yii::app()->clientScript->render($sc);
			echo $form.$sc;
			Yii::app()->end();
		}

		$this->render('fields/add', array(
			'product' => $product,
			'form' => $form,
		));
	}

	public function actionEditField($productId,$fieldId)
	{
		$product = Product::model()->findByPk($productId);
		$product->setScenario('edit');
		$productField = ProductField::model()->findByPk($fieldId);
		$productField->setScenario('edit');

		$arProductFieldForm = $productField->getMotelArrayCForm();

		$class = null;
		$fieldType = null;
		$className = null;
		$fieldType = $productField->field_type;
		if ( isset(TypeField::$Fields[$fieldType]['class']) ){
			$className = TypeField::$Fields[$fieldType]['class'];
			$class = $productField::CreateField($fieldType);
			$class = $class::model()->findByPk($fieldId);
			$arProductFieldForm['elements'][$className] = $class->getElementsMotelCForm();
			$productField->moredata = $class;
		}


		$form = new CForm($arProductFieldForm);
		$form['productField']->model = $productField;

		if ( $class ) $form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($productField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$className];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $form->submitted() && $productField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$product->id)));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		$this->render('fields/edit', array(
			'product' => $product,
			'form' => $form,
		));
	}

	public function actionDeleteField($productId,$fieldId)
	{
		$productField = ProductField::model()->find('id=:id AND product_id=:product_id',array(':id'=>$fieldId,':product_id'=>$productId));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $productField && $productField->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$productId)));
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}

    public function actionExport($id){
        $product = Product::model()->findByPk($id);

        $export = new Export();
        $export->fields = $product->fields;

        $form = $export->getExportMotelCForm();

        $this->performAjaxValidation($export);

        if( $form->submitted() && $form->validate() ){
			$model = $product->getRecordObject();

			if ( in_array($export->exportType,array(0,1))  ) {

				$columns = array();
				foreach ($export->exportFields as $fieldId) {
					$field = $product->fields[$fieldId];
					$columns[$fieldId]['name']=$field->alias;
					$columns[$fieldId]['type']='text';
					switch( $field->field_type ){
						case TypeField::STRING:
						case TypeField::PRICE:
						case TypeField::TEXT:
						case TypeField::INTEGER:
						case TypeField::DOUBLE:
							$columns[$fieldId]['value'] = '$data->'.$field->alias;
						break;
						case TypeField::BOOLEAN:
							$columns[$fieldId]['value'] = 'BooleanField::getValues($data->'.$field->alias.')';
						break;
						case TypeField::LISTS:
							if ($field->is_multiple_select)
								$columns[$fieldId]['value'] = '$data->getRecordItems("'.$field->alias.'")';
							else
								$columns[$fieldId]['value'] = 'isset($data->'.$field->alias.') ? $data->'.$field->alias.' : null';
						break;
						default :
							$columns[$fieldId]['value'] = '';

					}
				}

				$type = array('Excel5','CSV');
				$this->widget('ext.EExcelView.EExcelView', array(
								'dataProvider'=> $model->search(),
								'grid_mode'=>'export',
								'title'=> $product->name ,
								'filename'=> $product->alias ,
								'stream'=>true,
								'exportType'=>$type[$export->exportType],
								'columns'=> $columns,
				));
			}
        }


    	$this->render('records/export', array(
			'product' => $product,
            'form' => $form,
		));
    }

    public function actionImport($id){
        $product = Product::model()->findByPk($id);


        $this->render('records/export', array(
			'product' => $product
		));
    }

}
