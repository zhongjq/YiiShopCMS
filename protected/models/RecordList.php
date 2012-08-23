<?php

/**
 * This is the model class for table "RecordsLists".
 *
 * The followings are the available columns in table 'RecordsLists':
 * @property integer $product_id
 * @property integer $record_id
 * @property integer $list_item_id
 *
 * The followings are the available model relations:
 * @property ListsItems $listItem
 * @property Products $product
 */
class RecordList extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RecordsLists the static model class
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
		return 'record_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, record_id, list_item_id', 'required'),
			array('product_id, record_id, list_item_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, record_id, list_item_id', 'safe', 'on'=>'search'),
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
			'listItem' => array(self::BELONGS_TO, 'ListsItems', 'list_item_id'),
			'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
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
			'list_item_id' => 'List Item',
		);
	}

}