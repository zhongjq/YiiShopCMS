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

	public function actionFieldForm()
	{
		$model = new ProductFieldForm();

		$form = new CForm(array(

			'elements'=>array(
				'name'=>array(
					'type'=>'text',
					'maxlength'=>32,
				),
				'phone'=>array(
					'type'=>'password',
					'maxlength'=>32,
				),
				'timeToCall'=>array(
					'type'=>'checkbox',
				)
			),

			'buttons'=>array(
				'login'=>array(
					'type'=>'submit',
					'label'=>'Вход',
					'class'=>"btn"
				),
			),
		), $model);

		$this->renderPartial('/product/ProductFieldDialog',array('form'=>$form),false,true);


	}

}