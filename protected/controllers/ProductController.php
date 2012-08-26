<?php

class ProductController extends Controller
{

	public function actionIndex($Alias)
	{
		$Product = Products::model()->find('alias LIKE :alias', array(':alias'=>$Alias));

		$this->render('index',array("Product"=>$Product));
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
