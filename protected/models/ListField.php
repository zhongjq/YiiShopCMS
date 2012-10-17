<?php

/**
 * This is the model class for table "list_field".
 *
 * The followings are the available columns in table 'list_field':
 * @property string $field_id
 * @property string $list_id
 * @property integer $is_multiple_select
 *
 * The followings are the available model relations:
 * @property List $list
 * @property ProductField $field
 */
class ListField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ListField the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'list_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
    		array('field_id, list_id', 'required'),
			array('list_id', 'numerical', 'allowEmpty'=>false, 'message'=> Yii::t('fields','Select list') ),
			array('list_id, is_multiple_select', 'numerical', 'integerOnly'=>true),
			array('field_id, list_id, is_multiple_select', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'list' => array(self::BELONGS_TO, 'List', 'list_id'),
			'field' => array(self::BELONGS_TO, 'ProductField', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
    		'list_id' => Yii::t('fields','List'),
			'is_multiple_select' => Yii::t('fields','Multiple Select'),
		);
	}

    // форма в формате CForm
    public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'list_id'=> array(
    		    	'type' => 'dropdownlist',
				    'items' => CHtml::listData(Lists::model()->findAll(), 'id', 'name'),
				    'empty'=> '',
			    ),
				'is_multiple_select'=>array(
    				'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
	}
}