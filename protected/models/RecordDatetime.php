<?php

/**
 * This is the model class for table "RecordsCategories".
 *
 * The followings are the available columns in table 'RecordsCategories':
 * @property integer $product_id
 * @property integer $record_id
 * @property string $category_id
 *
 * The followings are the available model relations:
 * @property Categories $category
 */
class RecordDatetime extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'record_datetime';
	}

	public function rules()
	{
		return array(
			array('product_id, record_id', 'required'),
			array('product_id, record_id', 'numerical', 'integerOnly'=>true),
            array('date', 'date'),
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
			'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
		);
	}    
    
}