<?php

class ProductsController extends Controller
{

	public function actionIndex($Alias)
	{
		$Product = Products::model()->find('Alias LIKE :Alias', array(':Alias'=>$Alias));

		Yii::app()->clientScript->registerMetaTag($Product->Keywords, 'keywords');
		Yii::app()->clientScript->registerMetaTag($Product->Description, 'description');

		$this->render('index',array("Product"=>$Product));
	}
}
