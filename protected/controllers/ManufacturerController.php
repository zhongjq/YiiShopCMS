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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($alias)
	{
		$manufacturer = Manufacturer::model()->find('alias = :alias', array(':alias'=>$alias));

		if ( !$manufacturer ) throw new CHttpException(404,'Manufacturer not found.');

		$parent = $manufacturer->parent()->find();
		if ( $parent ) $parent = $parent->id; else $parent = null;
        // получаем все продукты где есть поле производитель
        $products = Product::model()->with(array('productFields','productFields.manufacturerField'))
						->findAll(	'productFields.field_type = :field_type AND manufacturerField.manufacturer_id = :manufacturer_id',
									array(':field_type'=>TypeField::MANUFACTURER,
											':manufacturer_id'=>$parent,
										));

        $arProducts = array();

        if ( $products ){
            foreach($products as $product){
                $arProducts[] = $product->searchByManufacturer($manufacturer->id);
            }
        }


		$this->render('view',array(
			'manufacturer' => $manufacturer,
            'products'=>$arProducts,
		));
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Categories');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
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
