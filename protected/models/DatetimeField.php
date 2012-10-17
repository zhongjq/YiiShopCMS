<?php

/**
 * This is the model class for table "datetime_field".
 *
 * The followings are the available columns in table 'datetime_field':
 * @property string $field_id
 * @property string $type
 * @property string $format
 * @property integer $is_multiple_select
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class DatetimeField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DatetimeField the static model class
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
		return 'datetime_field';
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
			array('is_multiple_select', 'numerical', 'integerOnly'=>true),
			array('field_id, type', 'length', 'max'=>11),
			array('format', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, type, format, is_multiple_select', 'safe', 'on'=>'search'),
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
			'field_id' => 'Field',
			'type' => 'Type',
			'format' => 'Format',
			'is_multiple_select' => 'Is Multiple Select',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('field_id',$this->field_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('is_multiple_select',$this->is_multiple_select);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}