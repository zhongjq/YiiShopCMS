<?php

/**
 * This is the model class for table "TextFields".
 *
 * The followings are the available columns in table 'TextFields':
 * @property integer $field_id
 * @property integer $min_length
 * @property integer $max_length
 * @property integer $rows
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class TextField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TextFields the static model class
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
		return 'text_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('min_length, max_length, rows', 'required', 'on'=>'add'),
			array('field_id, min_length, max_length, rows', 'required', 'on'=>'edit'),
			array('field_id, min_length, max_length, rows', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, min_length, max_length, rows', 'safe', 'on'=>'search'),
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
			'field' => array(self::BELONGS_TO, 'product_field', 'field_id'),
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
			'rows' => Yii::t('fields','Rows'),
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
				'rows'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
			)
		);
	}
}