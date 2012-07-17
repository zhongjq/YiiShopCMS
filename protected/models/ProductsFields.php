<?php

/**
 * This is the model class for table "ProductsFields".
 *
 * The followings are the available columns in table 'ProductsFields':
 * @property integer $ID
 * @property integer $ProductID
 * @property integer $FieldType
 * @property string $Name
 * @property integer $IsMandatory
 * @property integer $IsFilter
 *
 * The followings are the available model relations:
 * @property Products $product
 */
class ProductsFields extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductsFields the static model class
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
		return 'ProductsFields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ProductID, FieldType, Name, Alias', 'required', 'on'=>'add'),
			array('FieldType, Name, Alias', 'required', 'on'=>'validate'),
			array('ProductID, FieldType, IsMandatory, IsFilter', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, ProductID, FieldType, Name, IsMandatory, IsFilter', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Products', 'ProductID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'ProductID' => 'Product',
			'FieldType' => 'Field Type',
			'Name' => 'Name',
			'IsMandatory' => 'Is Mandatory',
			'IsFilter' => 'Is Filter',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('ProductID',$this->ProductID);
		$criteria->compare('FieldType',$this->FieldType);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('IsMandatory',$this->IsMandatory);
		$criteria->compare('IsFilter',$this->IsFilter);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}