<?php

/**
 * This is the model class for table "integer_field".
 *
 * The followings are the available columns in table 'integer_field':
 * @property string $field_id
 * @property integer $min_value
 * @property integer $max_value
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class IntegerField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IntegerField the static model class
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
		return 'integer_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
    		array('field_id', 'required', 'on'=>'add, edit'),
			array('field_id, min_value, max_value', 'numerical','integerOnly'=>true),
			array('min_value, max_value', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true ),
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
			'field' => array(self::BELONGS_TO, 'ProductField', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
    		'min_value' => Yii::t('fields','Min value'),
			'max_value' => Yii::t('fields','Max value'),
		);
	}

    // форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'min_value'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
				'max_value'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}
}