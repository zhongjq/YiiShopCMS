<?php

class Import extends CModel {

	public $file = null;
	public $step = 1;
	public $importType = null;
    public static $importTypes = array(0=>'xls',1=>'csv');


    public $fields = null;
    public $importFields = null;

    public function rules()
    {
        return array(
			array('step, importType', 'required'),

			array('file', 'required', 'on'=>'step_1'),
			array('file', 'file', 'types'=>'xls, csv','safe'=>true,'on'=>'step_1'),

        );
    }

    public function attributeNames(){
        return array(
			'step',
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

}