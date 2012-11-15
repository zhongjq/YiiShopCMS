<?php

/**
 * This is the model class for table "manufacturer_field".
 *
 * The followings are the available columns in table 'manufacturer_field':
 * @property string $field_id
 * @property string $manufacturer_id
 * @property integer $is_multiple_select
 *
 * The followings are the available model relations:
 * @property ProductField $field
 * @property Manufacturer $manufacturer
 */
class ManufacturerField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ManufacturerField the static model class
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
		return 'manufacturer_field';
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
			array('field_id', 'length', 'max'=>11),
			array('manufacturer_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, manufacturer_id, is_multiple_select', 'safe', 'on'=>'search'),
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
			'manufacturer' => array(self::BELONGS_TO, 'Manufacturer', 'manufacturer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'field_id' => 'Field',
			'manufacturer_id' => 'Manufacturer',
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
		$criteria->compare('manufacturer_id',$this->manufacturer_id,true);
		$criteria->compare('is_multiple_select',$this->is_multiple_select);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    // форма в формате CForm
    public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'manufacturer_id'=> array(
    		    	'type' => 'dropdownlist',
				    'items' => CHtml::listData(Manufacturer::model()->findAll(array('order'=>'lft')), 'id', 'name'),
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