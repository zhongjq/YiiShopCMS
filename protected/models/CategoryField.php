<?php

/**
 * This is the model class for table "category_field".
 *
 * The followings are the available columns in table 'category_field':
 * @property string $field_id
 * @property string $category_id
 * @property integer $is_multiple_select
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property ProductField $field
 */
class CategoryField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CategoryField the static model class
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
		return 'category_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_id, category_id', 'required'),
			array('is_multiple_select', 'numerical', 'integerOnly'=>true),
			array('field_id, category_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, category_id, is_multiple_select', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'field' => array(self::BELONGS_TO, 'ProductField', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'field_id' => 'Field',
			'category_id' => 'Category',
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('is_multiple_select',$this->is_multiple_select);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}