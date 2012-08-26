<?php

/**
 * This is the model class for table "boolean_field".
 *
 * The followings are the available columns in table 'boolean_field':
 * @property string $field_id
 * @property integer $default
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class BooleanField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BooleanField the static model class
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
		return 'boolean_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_id', 'required','on'=>'edit'),
			array('field_id, default', 'numerical', 'integerOnly'=>true),
			array('field_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, default', 'safe', 'on'=>'search'),
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
			'default' => Yii::t('field','Default'),
		);
	}

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'default'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}
}