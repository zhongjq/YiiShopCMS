<?php

/**
 * This is the model class for table "IntegerFields".
 *
 * The followings are the available columns in table 'IntegerFields':
 * @property integer $FieldID
 * @property integer $MinLength
 * @property integer $MaxLength
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class IntegerFields extends CActiveRecord
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
		return 'IntegerFields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MinValue, MaxValue', 'required', 'on'=>'add'),
			array('FieldID, MinValue, MaxValue', 'required', 'on'=>'edit'),
			array('FieldID, MinValue, MaxValue', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('FieldID, MinValue, MaxValue', 'safe', 'on'=>'search'),
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
			'field' => array(self::BELONGS_TO, 'ProductsFields', 'FieldID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'FieldID' => 'Field',
			'MinValue' => 'Min Value',
			'MaxValue' => 'Max Value',
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

		$criteria->compare('FieldID',$this->FieldID);
		$criteria->compare('MinValue',$this->MinLength);
		$criteria->compare('MaxValue',$this->MaxLength);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'MinValue'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
				'MaxValue'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}

}