<?php

class CartController extends Controller
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
		return array(
			array('allow','actions'=>array('add'),'users'=>array('*')),
			array('deny','users'=>array('*')),
		);
	}
    
    public function actionAdd($product,$id)
	{
        $this->redirect(Yii::app()->request->urlReferrer);
	}
    
}
