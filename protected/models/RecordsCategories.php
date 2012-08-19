<?php

/**
 * This is the model class for table "RecordsCategories".
 *
 * The followings are the available columns in table 'RecordsCategories':
 * @property integer $ProductID
 * @property integer $RecordID
 * @property string $CategoryID
 *
 * The followings are the available model relations:
 * @property Categories $category
 */
class RecordsCategories extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RecordsCategories the static model class
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
		return 'RecordsCategories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ProductID, RecordID, CategoryID', 'required'),
			array('ProductID, RecordID', 'numerical', 'integerOnly'=>true),
			array('CategoryID', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ProductID, RecordID, CategoryID', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Categories', 'CategoryID'),
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
			'CategoryID' => 'Category',
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
		$criteria->compare('CategoryID',$this->CategoryID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}