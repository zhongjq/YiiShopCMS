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
		);
	}
}