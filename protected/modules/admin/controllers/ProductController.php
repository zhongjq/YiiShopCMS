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
	
	public function actionProduct()
	{
		$this->render('index');
	}

	public function actionAdd()
	{
		$this->render('index');
	}

	public function actionCreate()
	{
		$Product = new Products();

		$Name = new ProductsFields();
		$Name->FieldType    =   TypeFields::STRING;
		$Name->Name         =   "Название";
		$Name->IsMandatory  =   true;
		$Name->IsSystem     =   true;
		$Name->Alias        =   "Name";
		$Product->addRelatedRecord("productsFields",$Name,0);

		$Price = new ProductsFields();
		$Price->FieldType    =   TypeFields::PRICE;
		$Price->Name         =   "Стоимость";
		$Price->IsMandatory  =   true;
		$Price->IsSystem     =   true;
		$Price->Alias        =   "Price";
		$Product->addRelatedRecord("productsFields",$Price,1);

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					if ( isset($_POST['Products']['ProductField']) )
						$ProductField = $_POST['Products']['ProductField'];
					else
						$ProductField = array();

					$Product->saveProductsFields($ProductField);
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('create', array( "Product" => $Product ) );
	}


	public function actionEdit($id){
		$Product = Products::model()->with('productsFields')->findByPk($id);

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					if ( isset($_POST['Products']['ProductField']) )
						$ProductField = $_POST['Products']['ProductField'];
					else
						$ProductField = array();

					$Product->saveProductsFields($ProductField);
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('edit', array( 'Product' => $Product ));
	}
}