<?php

class ProductController extends Controller
{
	public $layout='/layouts/main';

	public function actionIndex()
	{
		$criteria = new CDbCriteria();

		$count = Products::model()->count($criteria);

		$pages=new CPagination($count);
		// элементов на страницу
		$pages->pageSize=10;
		$pages->applyLimit($criteria);

		$Products = Products::model()->with('productsFields')->findAll($criteria);

		$this->render('index', array(
			'Products' => $Products,
			'pages' => $pages
		));
	}

	public function actionView($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Product->getAttributes();

		$Goods = $Product->getGoodsObject();
		$Goods = $Goods->findAll();

		$this->render('view', array(
			'Product' => $Product,
			'Goods' => $Goods,
		));
	}

	public function actionAdd($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "GoodsForm" )
		{
			echo CActiveForm::validate($Goods);
			Yii::app()->end();
		}

		if(isset($_POST[$Goods->tableName()])) {
			$Goods->attributes = $_POST[$Goods->tableName()];
			if(isset($_POST['submit']) && $Goods->save()){
				$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
			}
		}

		$Form = $Goods->getMotelCForm();

		$this->render('AddRecord',array('Product'=>$Product,'Form'=>$Form));
	}

    public function actionEditRecord($ProductID,$RecordID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();
        $Goods = $Goods->findByPk($RecordID);
        $Goods->setProductID($ProductID);
        
		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "GoodsForm" )
		{
			echo CActiveForm::validate($Goods);
			Yii::app()->end();
		}

		if(isset($_POST[$Goods->tableName()])) {
			$Goods->attributes = $_POST[$Goods->tableName()];
			if(isset($_POST['submit']) && $Goods->save()){
				$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
			}
		}

		$Form = $Goods->getMotelCForm();

		$this->render('EditRecord',array('Product'=>$Product,'Form'=>$Form));
	}

    public function actionDeleteRecord($ProductID,$RecordID)
    {
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();
        $Goods = $Goods->findByPk($RecordID);
        $Goods->setProductID($ProductID);        

		if( $Goods->delete() ){
			$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
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

		$Product = new Products('create');

		$this->performAjaxValidation($Product);

		$Form = $Product->getMotelCForm();

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					Yii::app()->db->createCommand()->createTable($Product->Alias, array(
						'ID' => 'pk',
						'Alias' => 'varchar(255)',
						'Title' => 'text',
						'Keywords' => 'text',
						'Description' => 'text',
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

		$this->render('create', array('Form'=>$Form) );
	}

	public function actionDelete($id)
	{
		Products::model()->findByPk($id)->delete();
		$this->redirect(array('/admin/product'));
	}

	public function actionEdit($id)
	{
		$Product = Products::model()->findByPk($id);

		$this->performAjaxValidation($Product);

		$Form = $Product->getMotelCForm();

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('edit', array('Form'=>$Form,'Product' => $Product) );
	}


	public function actionFields($id)
	{
		$Product = Products::model()->with('productsFields')->findByPk($id);

		$this->render('fields', array(
			'Product' => $Product
		));
	}

	public function actionAddField($id)
	{
		$Product = Products::model()->findByPk($id);
		$ProductField = new ProductsFields('add');
		$ProductField->ProductID = $id;

		$ArrayForm = array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'FieldForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "FieldForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
				'ProductsFields'=> array(
					'type'=>'form',
					'elements'=>array(
						'FieldType'=>array(
							'type'  =>  'dropdownlist',
							'items' =>  TypeFields::getFieldsList(),
							'empty'=>  '',

							'ajax' => array(
								'type'  =>  'POST',
								'url'   =>  "",
								'replace'=>  '#FieldForm',
							)

						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
						'Alias'=>array(
							'type'      =>  'text',
							'maxlength' =>  255,
						),
						'IsMandatory'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsFilter'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsColumnTable'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
					)
				)
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  'Создать',
					'class' =>  "btn"
				),
			),
		);

		$class = null;
		$FieldType = null;
		if ( isset($_POST['ProductsFields']['FieldType']) )
			$FieldType = $_POST['ProductsFields']['FieldType'];

		if ( $FieldType > 0 ){
			$ClassName = TypeFields::$Fields[$FieldType]['class'];
			$class = $ProductField::CreateField($FieldType);
			$ArrayForm['elements'][$ClassName] = $class->getElementsMotelCForm();

			$ProductField->moredata = $class;
		}

		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;
		if( Yii::app()->request->isAjaxRequest && $FieldType > 0  ){
			$Form[$ClassName]->model = $class;
		}

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];

			if ( isset($_POST[$ClassName]) ){
				$class->attributes = $_POST[$ClassName];
			}

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( isset($_POST[$ClassName]) && $ProductField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$Product->ID)));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}


		if( Yii::app()->request->isAjaxRequest && $FieldType > 0 ){
			$Form = $Form->render();
			$sc = '';
			Yii::app()->clientScript->render($sc);
			echo $Form.$sc;
			Yii::app()->end();
		}

		$this->render('addfield', array(
			'Product' => $Product,
			'Form' => $Form,
		));
	}

	public function actionEditField($id,$FieldID)
	{
		$Product = Products::model()->findByPk($id);
		$Product->setScenario('edit');
		$ProductField = ProductsFields::model()->findByPk($FieldID);
		$ProductField->setScenario('edit');

		$ArrayForm = array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'FieldForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "FieldForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
				'ProductsFields'=> array(
					'type'=>'form',
					'elements'=>array(
						'FieldType'=>array(
							'type'  =>  'dropdownlist',
							'items' =>  TypeFields::getFieldsList(),
							'empty'=>  '',
							'disabled'=>'disabled',
							'ajax' => array(
								'type'  =>  'POST',
								'url'   =>  "",
								'update'=>  '#FieldForm',
							)

						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
						'Alias'=>array(
							'type'      =>  'text',
							'maxlength' =>  255,
							'disabled'=>'disabled',
						),
						'IsMandatory'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsFilter'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsColumnTable'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
					)
				)
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  'Сохранить',
					'class' =>  "btn"
				),
			),
		);

		$FieldType = $ProductField->FieldType;
		$ClassName = TypeFields::$Fields[$FieldType]['class'];
		$class = $ProductField::CreateField($FieldType);
		$class = $class::model()->findByPk($FieldID);
		$ArrayForm['elements'][$ClassName] = $class->getElementsMotelCForm();
		$ProductField->addRelatedRecord('moredata',$class,true);

		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;
		$Form[$ClassName]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];

			if ( isset($_POST[$ClassName]) ){
				$class->attributes = $_POST[$ClassName];
			}

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( isset($_POST[$ClassName]) && $ProductField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$Product->ID)));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		$this->render('addfield', array(
			'Product' => $Product,
			'Form' => $Form,
		));
	}

	public function actionDeleteField($id,$FieldID)
	{
		$ProductField = ProductsFields::model()->find('ID=:ID AND ProductID=:ProductID',array(':ID'=>$FieldID,'ProductID'=>$id));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $ProductField->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$id)));
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}


	/// LIST
	public function actionLists(){

		$this->render('lists/index', array() );
	}
}