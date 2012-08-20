<?php

/**
 * This is the model class for table "StringFields".
 *
 * The followings are the available columns in table 'StringFields':
 * @property integer $field_id
 * @property integer $min_length
 * @property integer $max_length
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class StringField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StringFields the static model class
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
		return 'string_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('min_length, max_length', 'required', 'on'=>'add'),
			array('field_id, min_length, max_length', 'required', 'on'=>'edit'),
			array('field_id, min_length, max_length', 'numerical', 'integerOnly'=>true, 'min'=>0, 'max'=>255 ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, min_length, max_length', 'safe', 'on'=>'search'),
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
			'min_length' => Yii::t('fields','Min length'),
			'max_length' => Yii::t('fields','Max length'),
		);
	}

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
				'type'=>'form',
				'elements'=>array(
					'min_length'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
					'max_length'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
				)
			);
	}

}