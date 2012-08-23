<?php

/**
 * This is the model class for table "Lists".
 *
 * The followings are the available columns in table 'Lists':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ListsItems[] $listsItems
 */
class Lists extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lists the static model class
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
		return 'list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required', 'on'=> 'add, edit'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'listsItems' => array(self::HAS_MANY, 'ListItem', 'list_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('lists',"ID"),
			'name' => Yii::t('lists',"Name"),
		);
	}

    public function getCFormArray(){
        return array(
            'attributes' => array(
    			'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'listsForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "listsForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
				'name'=>array(
					'type'=>'text',
					'maxlength' =>255,
                    'placeholder'=>"name"
				)
			),

			'buttons'=>array(
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' =>  "btn"
				),
			),
		);
    }

    public function afterDelete(){
		parent::afterDelete();
		ListItem::model()->findAll('list_id = :id',array(':id'=>$this->id));
	}

}