<?php

/**
 * This is the model class for table "image_field".
 *
 * The followings are the available columns in table 'image_field':
 * @property integer $field_id
 * @property integer $is_multiple_select
 * @property integer $quantity
 *
 * The followings are the available model relations:
 * @property ProductField $field
 * @property ImageFieldParameter[] $imageFieldParameters
 */
class ImageField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImageField the static model class
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
		return 'image_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_id', 'required', 'on'=>'edit'),
			array('field_id, is_multiple_select, quantity', 'numerical', 'integerOnly'=>true),
			array('quantity', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, is_multiple_select, quantity', 'safe', 'on'=>'search'),
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
			'imageFieldParameters' => array(self::HAS_MANY, 'ImageFieldParameter', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'is_multiple_select' => 'Is Multiple Select',
			'quantity' => 'Quantity',
		);
	}

    public function getElementCForm(){
	    return array(
    		'type' => 'Files',
            'accept'=>'jpg|gif|png',
        );
    }

	// форма в формате CForm
	public function getElementsMotelCForm(){        
		return array(
				'type'=>'form',
				'elements'=>array(
					'quantity'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
				)
			);
	}
}