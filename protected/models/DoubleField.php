<?php

/**
 * This is the model class for table "double_field".
 *
 * The followings are the available columns in table 'double_field':
 * @property string $field_id
 * @property string $decimal
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class DoubleField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DoubleField the static model class
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
		return 'double_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
    		array('field_id', 'required'),
			array('field_id, decimal', 'length', 'max'=>11),
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
			'decimal' => Yii::t('fields','Количество знаков после запятой'),
		);
	}

    // форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'decimal'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}
}