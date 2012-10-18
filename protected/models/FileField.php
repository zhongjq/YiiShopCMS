<?php

/**
 * This is the model class for table "file_field".
 *
 * The followings are the available columns in table 'file_field':
 * @property string $field_id
 * @property string $file_type
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class FileField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FileField the static model class
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
		return 'file_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file_type', 'required'),
			array('field_id, file_type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, file_type', 'safe', 'on'=>'search'),
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
			'file_type' => Yii::t('fields','File Type'),
		);
	}

    public static function getTypesFiles($value = -1){
    	$v = array( 0 => Yii::t("main","Images"), 
                    1 => Yii::t("main","Documents") 
                    );

		if ( is_numeric($value) && $value == -1)
			return $v;
		elseif ( is_numeric($value) )
			return $v[$value];
		
	}

    // форма в формате CForm
    public function getElementsMotelCForm(){
    	return array(
			'type'=>'form',
			'elements'=>array(
				'file_type'=> array(
    		    	'type' => 'dropdownlist',
				    'items' => self::getTypesFiles(),
				    'empty'=> '',
			    )
			)
		);
	}
}