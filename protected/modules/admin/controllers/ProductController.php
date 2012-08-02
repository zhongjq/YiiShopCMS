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


	public static function asd($a){
		return $a;
	}

	public function actionView($id)
	{
		$Product = Products::model()->with('productsFields')->findByPk($id);
		$Product->getAttributes();

		$Goods = $Product->getGoodsObject();
		$Goods = $Goods->findAll(array(
			'select'=>'t.Name'
		));

		$this->render('view', array(
			'Product' => $Product,
			'Goods' => $Goods,
		));
	}

	public function actionAdd($id)
	{
		$Product = Products::model()->with('productsFields')->findByPk($id);
		$Goods = $Product->getGoodsObject();

		if(isset($_POST[$Goods->tableName()])) {
			$Goods->attributes = $_POST[$Goods->tableName()];
			if(isset($_POST['submit']) && $Goods->save()){
				$this->redirect($this->createUrl('/admin/product/view',array('id'=>$Product->ID)));
			}
		}

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "GoodsForm" )
		{
			echo CActiveForm::validate($Goods);
			Yii::app()->end();
		}

		$Form = $Goods->getMotelCForm();

		$this->render('add',array('Product'=>$Product,'Form'=>$Form));
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
		if( Yii::app()->request->isAjaxRequest && isset($_POST['ProductsFields']['FieldType']) ){
			$ClassName = 'StringFields';
			$class = $ProductField::CreateField(2);
			$ArrayForm['elements'][$ClassName] = $class->getElementsMotelCForm();
		}

		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ProductsFields']['FieldType']) ){
			$Form[$ClassName]->model = $class;
		}

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" )
		{
			echo CActiveForm::validate(array($ProductField,$class));
			Yii::app()->end();
		}

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ProductsFields']['FieldType']) ){
			$Form->render();
			echo $Form->renderBody();
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

		$ProductField = ProductsFields::model()->findByPk($FieldID);
		$ProductField->ProductID = $id;

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" )
		{
			echo CActiveForm::validate($ProductField);
			Yii::app()->end();
		}

		if(isset($_POST['ProductsFields'])) {
			$ProductField->attributes = $_POST['ProductsFields'];
			if($ProductField->save()){
				$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$id)));
			}
		}

		$Form = $ProductField->getMotelCForm();

		if( Yii::app()->request->isAjaxRequest ){
			$Form->render();
			echo $Form->renderElements().$Form->renderButtons();
			Yii::app()->end();
		}

		$this->render('editfield', array(
			'Product' => $Product,
			'Form' => $Form,
		));
	}

	public function actionDeleteField($id,$FieldID)
	{


		$ProductField = ProductsFields::model()->find('ID=:ID AND ProductID=:ProductID',array(':ID'=>$FieldID,'ProductID'=>$id));

		if($ProductField->delete()){
			$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$id)));
		}
	}


}