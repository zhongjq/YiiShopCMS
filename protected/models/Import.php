<?php

class Import extends CModel {

	public $file = null;
	public $importType = null;
    public static $importTypes = array(0=>'xls',1=>'csv');


    public $fields = null;
    public $importFields = null;

    public function rules()
    {
        return array(
			array('file', 'required'),
			array('file', 'file', 'types'=>'xls, csv'),

            array('importFields', 'ArrayValidator', 'validator'=>'numerical', 'params'=>array('integerOnly'=>true) ),
        );
    }

    public function attributeNames(){
        return array(
            'file',
            'importType',
            'importFields',
        );
    }
    public function attributeLabels(){
        return array(
            'file' => Yii::t('record','File'),
            'importType' => Yii::t('record','Export type'),
            'importFields' => Yii::t('record','Export fields'),
        );
    }

    public function getStepOneCForm()
    {
    	$form = array(
			'attributes' => array(
                'id' => "importForm",
                'class' => 'well',
				'enctype' => 'multipart/form-data',
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
                'file' => array(
        	    	'type' => 'file'
			    )
            ),
			'buttons' => array(
				'<br/>',
				'submit'=>array(
					'type' => 'submit',
					'label' => Yii::t("product","Step 1"),
					'class' => "btn"
				),
			),
		);


		return new CForm($form,$this);
	}

}