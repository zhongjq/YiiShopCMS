<?php

class Import extends CModel {

	public $file = null;
	public $step = 1;
	public $importType = null;
    public static $importTypes = array(0=>'xls',1=>'csv');


    public $fields = null;
    public $importFields = null;
    public $countImportFields = null;
    public $fileFields = null;

    public function rules()
    {
        return array(
			array('step, importType', 'required'),

			array('file', 'required', 'on'=>'step_1'),
			array('countImportFields', 'required', 'on'=>'step_2'),
			array('countImportFields', 'numerical', 'integerOnly'=>true),
			array('file', 'file', 'types'=>'xls, csv','safe'=>true,'on'=>'step_1'),

			array('importFields, file','required', 'on'=>'step_2' ),
            array('importFields','ImportValidator', 'on'=>'step_2' ),
			array('importFields, file','safe' ),
        );
    }

    public function ImportValidator($attribute,$params)
    {
		if ( !is_array($this->$attribute) )
			$this->addError($attribute,'Необходимо выбрать соответсвия полей.');

		foreach ($this->$attribute as &$value) {
			if( !isset($value['to']) || !isset($value['from']) || !is_numeric($value['to']) || !is_numeric($value['from']) )
				$this->addError($attribute,'Необходимо выбрать соответсвия полей.');
		}

    }

    public function attributeNames(){
        return array(
			'step',
            'file',
            'importType',
            'importFields',
            'countImportFields',
        );
    }
    public function attributeLabels(){
        return array(
            'file' => Yii::t('record','File'),
            'importType' => Yii::t('record','Export type'),
            'importFields' => Yii::t('record','Import fields'),
            'countImportFields' => Yii::t('record','countImportFields'),
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
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),
			'elements' => array(
                'step' => array(
        	    	'type' => 'hidden'
			    ),
                'importType' => array(
        	    	'type' => 'dropdownlist',
				    'items' => self::$importTypes,
				    'empty'=> '',
			    ),
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


    public function getStepTwoCForm()
    {

    	$form = array(
			'attributes' => array(
                'id' => "importForm",
                'class' => 'well',
				'enctype' => 'multipart/form-data',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),
			'elements' => array(
                'step' => array(
        	    	'type' => 'hidden'
			    ),
                'importType' => array(
        	    	'type' => 'dropdownlist',
				    'items' => self::$importTypes,
				    'empty'=> '',
			    ),
                'file' => array(
        	    	'type' => 'text',
			    ),
                'countImportFields' => array(
        	    	'type' => 'text',
			    ),
				'importFields' => array(
                    'type' => 'ImportFields'
                )
            ),
			'buttons' => array(
				'<br/>',
				'submit'=>array(
					'type' => 'submit',
					'label' => Yii::t("product","Step 2"),
					'class' => "btn"
				),
			),
		);


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