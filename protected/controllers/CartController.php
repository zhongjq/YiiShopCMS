<?php

class CartController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules(){
		return array(
			array('allow','actions'=>array('add'),'users'=>array('*')),
			array('deny','users'=>array('*')),
		);
	}

    public function actionIndex($product,$id){
		Cart::model()->
	}

    public function actionAdd($product,$id){
		if (Cart::model()->add($product,$id) ){
			$this->redirect(Yii::app()->request->urlReferrer);
		}
	}

}
