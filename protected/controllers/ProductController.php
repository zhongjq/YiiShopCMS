<?php

class ProductController extends Controller
{
    public $layout='//layouts/column1';
    
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
				'actions'=>array('index','view','viewId','viewAlias'),
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
    
	public function actionIndex($alias)
	{
		$product = Product::model()->find('alias LIKE :alias', array(':alias'=>$alias));
        
        if ( $product ) {
        
            $records = $product->getRecordObject();
            
            $records->attributes = $_GET[get_class($records)];  
            
    		$this->render('index',array(
                "product"=>$product,
                "records"=>$records,
            ));
        }
	}

	public function actionViewId($product,$id)
	{
		$product = Product::model()->find('alias = :alias', array(':alias'=>$product));

		$record = $product->getRecordObject()->findByPk($id);

		$this->render('view',array(
			"product"=>$product,
			"record"=>$record,
		));
	}

	public function actionViewAlias($product,$alias)
	{
		$product = Product::model()->find('alias = :alias', array(':alias'=>$product));

		$record = $product->getRecordObject()->find('alias = :alias', array(':alias'=>$alias));

		$this->render('view',array(
			"product"=>$product,
			"record"=>$record,
		));
	}
}
