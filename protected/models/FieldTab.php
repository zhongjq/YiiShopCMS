<?php

/**
 * This is the model class for table "field_tab".
 *
 * The followings are the available columns in table 'field_tab':
 * @property string $field_id
 * @property string $tab_id
 * @property string $position
 *
 * The followings are the available model relations:
 * @property ProductField $field
 * @property Tab $tab
 */
class FieldTab extends CActiveRecord
{
    const tableName = 'field_tab';
    
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'field_tab';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_id', 'required'),
			array('field_id, tab_id, position', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('field_id, tab_id, position', 'safe', 'on'=>'search'),
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
			'tab' => array(self::BELONGS_TO, 'Tab', 'tab_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'field_id' => 'Field',
			'tab_id' => 'Tab',
			'position' => 'Position',
		);
	}

}