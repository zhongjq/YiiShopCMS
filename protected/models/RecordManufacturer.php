<?php

/**
 * This is the model class for table "record_manufacturer".
 *
 * The followings are the available columns in table 'record_manufacturer':
 * @property string $product_id
 * @property string $record_id
 * @property string $manufacturer_id
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Manufacturer $manufacturer
 */
class RecordManufacturer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RecordManufacturer the static model class
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
		return 'record_manufacturer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, record_id, manufacturer_id', 'required'),
			array('product_id, record_id, manufacturer_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, record_id, manufacturer_id', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
			'manufacturer' => array(self::BELONGS_TO, 'Manufacturer', 'manufacturer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'record_id' => 'Record',
			'manufacturer_id' => 'Manufacturer',
		);
	}

}