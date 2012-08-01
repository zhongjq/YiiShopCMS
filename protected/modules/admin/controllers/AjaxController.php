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
		$model = new ProductsFields('add');
		if (isset($_POST['ProductsFields'])) {
			$model->attributes = $_POST['ProductsFields'];
		}

		$form = new CForm(array(
			'attributes' => array(
				'enctype' => 'application/form-data',
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
				'FieldType'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  TypeFields:: getFieldsList(),
					'empty'=>  '',
					'empty'=>  '',

				),
				'Name'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'Alias'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'IsMandatory'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'IsFilter'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				)
			),

			'buttons'=>array(
				'<br/>',
				'login'=>array(
					'type'=>'submit',
					'label'=>'Вход',
					'class'=>"btn"
				),
			),
		), $model);


		if(isset($_POST['ajax']) && $_POST['ajax']==='FieldForm')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$this->renderPartial('/product/ProductFieldDialog',array('form'=>$form),false,true);


	}

}