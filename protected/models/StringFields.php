<?php

/**
 * This is the model class for table "StringFields".
 *
 * The followings are the available columns in table 'StringFields':
 * @property integer $FieldID
 * @property integer $MinLength
 * @property integer $MaxLength
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class StringFields extends CActiveRecord
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
		return 'StringFields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MinLength, MaxLength', 'required', 'on'=>'add'),
			array('FieldID, MinLength, MaxLength', 'required', 'on'=>'edit'),
			array('FieldID, MinLength, MaxLength', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('FieldID, MinLength, MaxLength', 'safe', 'on'=>'search'),
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
			'MinLength' => 'Min Length',
			'MaxLength' => 'Max Length',
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
		$criteria->compare('MinLength',$this->MinLength);
		$criteria->compare('MaxLength',$this->MaxLength);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
				'type'=>'form',
				'elements'=>array(
					'MinLength'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
					'MaxLength'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
				)
			);
	}

}