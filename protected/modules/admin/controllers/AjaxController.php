<?php

class AjaxController extends Controller
{
	public function actionValidateField()
	{
		$ProductField = new ProductsFields();
		$ProductField->setScenario('validate');
		$ProductField->attributes=$_POST['ProductField'];

		$return = array('success'=>true);
		if ( !$ProductField->validate() ){
			$return['success'] = false;
			$return['errors'] = $ProductField->getErrors();

		}

		echo json_encode( $return );
		Yii::app()->end();
	}
}