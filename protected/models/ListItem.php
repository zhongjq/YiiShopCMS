<?php

/**
 * This is the model class for table "ListsItems".
 *
 * The followings are the available columns in table 'ListsItems':
 * @property integer $ID
 * @property integer $list_id
 * @property integer $status
 * @property integer $priority
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Lists $list
 */
class ListItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ListsItems the static model class
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
		return 'list_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('list_id, name', 'required', 'on' => 'add, edit'),
			array('list_id, status, priority', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255, 'on' => 'add, edit'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, list_id, status, priority, name', 'safe', 'on'=>'search'),
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
			'list' => array(self::BELONGS_TO, 'Lists', 'list_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("lists","ID"),
			//'list_id' => 'List',
			'status' => Yii::t("lists","Status"),
			'priority' => Yii::t("lists","Priority"),
			'name' => Yii::t("lists","Name"),
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
		$criteria->compare('list_id',$this->list_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getCFormArray(){
        return array(
            'attributes' => array(
        		'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'itemForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "itemForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
    		    'status'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'priority'=>array(
					'type'          =>  'text',
					'maxlength'     =>  255,
				),
    			'name'=>array(
					'type'          =>  'text',
					'maxlength'     =>  255,
				),
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
}