<?php

/**
 * This is the model class for table "IntegerFields".
 *
 * The followings are the available columns in table 'IntegerFields':
 * @property integer $field_id
 * @property integer $MinLength
 * @property integer $MaxLength
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class DateTimeField extends CActiveRecord
{
    const DATETIME = 0;
    const DATE = 1;
    const TIME = 2;
    public static $formats = array(
        self::DATETIME => 'dd/MM/YYYY H:i',
        self::DATE => 'dd/MM/YYYY',
        self::TIME => 'H:i',
    );
    
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'datetime_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('min_value, max_value', 'required', 'on'=>'add'),
			array('field_id', 'required', 'on'=>'edit'),
			array('field_id, type, is_multiple_select', 'numerical','integerOnly'=>true),
            array('format', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, type', 'safe', 'on'=>'search'),
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
			'field' => array(self::BELONGS_TO, 'ProductsFields', 'field_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type' => Yii::t('fields','Type'),
			'format' => Yii::t('fields','Format'),
		);
	}
    
    public static function getTypeDateTime(){
        return array(
            self::DATETIME=>"Дата/время",
            self::TIME=>"Время",
            self::DATE=>"Дата"            
        );
    }
    
	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'type'=> array(
        	    	'type'  =>  'dropdownlist',
				    'items' =>  DateTimeField::getTypeDateTime(),
			    ),
				'format'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
    			'is_multiple_select'=>array(
    				'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
	}

}