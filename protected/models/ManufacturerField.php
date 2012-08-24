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
 * @property Manufacturer $manufacturer
 * @property ProductField $field
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
			array('field_id', 'required','on'=>'edit'),
			array('is_multiple_select', 'numerical', 'integerOnly'=>true),
			array('field_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, is_multiple_select', 'safe', 'on'=>'search'),
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
			'is_multiple_select' => 'Is Multiple Select',
		);
	}


    // форма в формате CForm
    public function getElementsMotelCForm(){
    	return array(
			'type'=>'form',
			'elements'=>array(
				'is_multiple_select'=>array(
				'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
    }
}