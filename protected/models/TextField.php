<?php

/**
 * This is the model class for table "text_field".
 *
 * The followings are the available columns in table 'text_field':
 * @property string $field_id
 * @property string $min_length
 * @property string $max_length
 * @property string $rows
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class TextField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TextField the static model class
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
			array('field_id', 'required'),
			array('field_id, min_length, max_length, rows', 'length', 'max'=>11),
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
			'min_length' => 'Min Length',
			'max_length' => 'Max Length',
			'rows' => 'Rows',
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
		$criteria->compare('min_length',$this->min_length,true);
		$criteria->compare('max_length',$this->max_length,true);
		$criteria->compare('rows',$this->rows,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}