<?php

/**
 * This is the model class for table "RecordsLists".
 *
 * The followings are the available columns in table 'RecordsLists':
 * @property integer $ProductID
 * @property integer $RecordID
 * @property integer $ListItemID
 *
 * The followings are the available model relations:
 * @property ListsItems $listItem
 * @property Products $product
 */
class RecordsLists extends CActiveRecord
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
		return 'RecordsLists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ProductID, RecordID, ListItemID', 'required'),
			array('ProductID, RecordID, ListItemID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ProductID, RecordID, ListItemID', 'safe', 'on'=>'search'),
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
			'listItem' => array(self::BELONGS_TO, 'ListsItems', 'ListItemID'),
			'product' => array(self::BELONGS_TO, 'Products', 'ProductID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ProductID' => 'Product',
			'RecordID' => 'Record',
			'ListItemID' => 'List Item',
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

		$criteria->compare('ProductID',$this->ProductID);
		$criteria->compare('RecordID',$this->RecordID);
		$criteria->compare('ListItemID',$this->ListItemID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}