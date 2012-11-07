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

			array('importFields','required', 'on'=>'step_2' ),
			array('importFields','safe' ),
        );
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
		$fieldMapping='';
		if( !empty($this->countImportFields) ){

$js = <<<JS
$(function(){
	jQuery("#addFile").click(function(){

		var tbody = $(this).closest("table").find("tbody");



		var tr = tbody.find("tr:last").clone()
					.find("select").attr('name', function(i, val) {
						var name = val.match(/[\d+]/)[0];
						return val.replace(/[\d+]/, parseInt(name) + 1 )
					}).end()
					.find("input").val("").end()
					.find("a").show().end();




	   tbody.append( tr );

	   return false;
	});
});

JS;

		$cs=Yii::app()->getClientScript();
		$cs->registerScript('files',$js);

			$name = CHtml::activeName($this,'importFields');

			$listFiels = array();
			for($i = 1; $i<=$this->countImportFields;$i++)
				$listFiels[] = Yii::t('product','Col #'.$i);

			$fieldMapping .= CHtml::tag("div",array('class'=>'row'));
			$fieldMapping .= CHtml::label("Field mapping", '');
			$fieldMapping .= CHtml::openTag('table',array('class'=>"table"));
				$fieldMapping .= CHtml::openTag('tbody',array());
					$fieldMapping .= CHtml::openTag('tr',array());
						$fieldMapping .= CHtml::openTag('td',array());
							$fieldMapping .= CHtml::dropDownList($name.'[0][to]',null, $listFiels,array('empty'=>'') );
						$fieldMapping .=  CHtml::closeTag('td');
						$fieldMapping .=  CHtml::openTag('td',array());
							$fieldMapping .= CHtml::dropDownList($name.'[0][param]',1, array(0=>"=",1=>">") );
						$fieldMapping .= CHtml::closeTag('td');
						$fieldMapping .= CHtml::openTag('td',array());
							$fieldMapping .= CHtml::dropDownList($name.'[0][from]',null, CHtml::listData($this->fields,'id','name'),array('empty'=>'') );
						$fieldMapping .= CHtml::closeTag('td');
					$fieldMapping .= CHtml::closeTag('tr');
				$fieldMapping .= CHtml::closeTag('tbody');

				$fieldMapping .= CHtml::openTag('tfoot',array());
					$fieldMapping .= CHtml::openTag('tr',array());
						$fieldMapping .= CHtml::openTag('td',array('colspan'=>3));
							$fieldMapping .= CHtml::link(Yii::t('fields', 'Еще'),"#", array('id'=>'addFile'));
						$fieldMapping .= CHtml::closeTag('td');
					$fieldMapping .= CHtml::closeTag('tr');
				$fieldMapping .= CHtml::closeTag('tfoot');

			$fieldMapping .= CHtml::closeTag("table");



			$fieldMapping .= CHtml::closeTag("div");
		}

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
				$fieldMapping,
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