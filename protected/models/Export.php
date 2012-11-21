<?php

class Export extends CModel {

    public $exportType = null;
    public static $exportTypes = array(0=>'xls',1=>'csv');


    public $fields = null;
    public $exportFields = null;

    public function rules()
    {
        return array(
            array('exportType,exportFields', 'required'),
            array('exportFields', 'ArrayValidator', 'validator'=>'numerical', 'params'=>array('integerOnly'=>true) ),
        );
    }

    public function attributeNames(){
        return array(
            'exportType',
            'exportFields',
        );
    }
    public function attributeLabels(){
        return array(
            'exportType' => Yii::t('record','Export type'),
            'exportFields' => Yii::t('record','Export fields'),
        );
    }

    public function getExportMotelCForm()
    {
    	$form = array(
			'attributes' => array(
                'id' => "exportForm",
                'class' => 'well',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),
			'elements' => array(
                'exportType' => array(
        	    	'type' => 'dropdownlist',
				    'items' => self::$exportTypes,
				    'empty'=> '',
			    ),
                'exportFields' => array(
                	'type' => 'dropdownlist',
				    'items' => CHtml::listData($this->fields, 'id', 'name'),
                    'multiple' => true,
				    'empty'=> '',
			    ),
            ),
			'buttons' => array(
				'<br/>',
				'submit'=>array(
					'type' => 'submit',
					'label' => Yii::t("product","Export"),
					'class' => "btn"
				),
			),
		);

        Yii::app()->clientScript->registerPackage('chosen');

        Yii::app()->getClientScript()->registerScript("select",'$(function(){$("select[multiple]").chosen({allow_single_deselect:true});});');

		return new CForm($form,$this);
	}
    
    public function setFields($fields){
        
        $this->fields[0] = (object)array('id'=>0,'name'=>"ID",'alias'=>"id",'field_type'=>TypeField::INTEGER);
        
    	foreach ($fields as $key => $field) {
			switch( $field->field_type ){
				case TypeField::FILE:
				break;
                default:                    
                    $this->fields[$key] = $field;
			}
		}        
        
    }
}