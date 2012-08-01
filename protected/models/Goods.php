<?php

class Goods extends CActiveRecord
{
	private $ProductID;
	private $ProductsFields = null;
	public  function setProductID($v){
		$this->ProductID = $v;
	}
	public function getProductID(){
		return $this->ProductID;
	}

	public function getProductFields($update = false){
		if ( $this->getProductID() === null ) throw new CException("ID NOT ProductID");

		if ( $this->ProductsFields === null && $update === false )
			$this->ProductsFields = ProductsFields::model()->findAll('ProductID=:ProductID',array(':ProductID'=>$this->getProductID()));

		return $this->ProductsFields;
	}

	public function setGoodsAttributes(){
		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$this->setAttribute($Field->Alias,$Field->Alias);
			}
		}
	}

	public function getMotelCForm(){

		$Form = array(
			'attributes'    =>  array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id' => "GoodsForm",
			),
			'activeForm'    =>  array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "GoodsForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),
			'elements'      =>  array(
				'<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab">Поля</a></li>
						<li><a href="#tab2" data-toggle="tab">SEO</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab1">
							<p>'
			),
			'buttons'       =>  array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' =>  "btn"
				),
			),
		);

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$Form['elements'][$Field->Alias] = TypeFields::$Fields[$Field->FieldType]['form'];
			}
		}


		$Form['elements'][]="</p></div>";


		$Form['elements'][]='<div class="tab-pane" id="tab2"><p>';
		$Form['elements']['Alias'] = array('type'=>'text','class'=>"span5",'maxlength' =>  255);
		$Form['elements']['Title'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['Keywords'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['Description'] = array('type'=>'textarea','class'=>"span5",'rows'=>5);
		$Form['elements'][]="</p></div>";


		$Form['elements'][]='</div></div>';

		return new CForm($Form,$this);
	}


	public function rules()
	{
		$rules = array();
		$required = array();
		$numerical = array();
		$safe = array('Title','Keywords','Description');
		$unique = array("Alias");

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				if ( $Field->IsMandatory ) $required[] = $Field->Alias;
				if ( $Field->FieldType == TypeFields::NUMERIC ) $numerical[] = $Field->Alias;
			}
		}

		if ( !empty($required) )
			$rules[] = array(implode(',',$required), 'required');

		if ( !empty($numerical) )
			$rules[] = array(implode(',',$numerical), 'numerical', 'integerOnly'=>true);

		if ( !empty($safe) )
			$rules[] = array(implode(',',$safe), 'safe');

		if ( !empty($unique) )
			$rules[] = array(implode(',',$unique), 'unique');

		$rules[] = array('Alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u',
						'message' => Yii::t("AdminModule.products",'Field contains invalid characters.'));

		return $rules;
	}

	public function attributeLabels()
	{
		$labels = array();

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$labels[$Field->Alias] = $Field->Name;
			}
		}

		return $labels;
	}


}
