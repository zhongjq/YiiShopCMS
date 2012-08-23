<?php

/**
 * This is the model class for table "category_field".
 *
 * The followings are the available columns in table 'category_field':
 * @property integer $field_id
 * @property integer $category_id
 *
 * The followings are the available model relations:
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
			array('category_id', 'required', 'on'=>'add'),
            array('field_id, category_id', 'required', 'on'=>'edit'),
			array('field_id, category_id, is_multiple_select', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, category_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'category_id' =>  Yii::t('fields',"Category"),
            'is_multiple_select'=> Yii::t('fields',"Is Multiple Select?")
		);
	}

    // форма в формате CForm
    public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'category_id'=> array(
    		    	'type'  =>  'dropdownlist',
				    'items' =>  CHtml::listData(Category::model()->findAll(), 'id', 'name'),
				    'empty'=>  '',
			    ),
				'is_multiple_select'=>array(
    				'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
	}
}