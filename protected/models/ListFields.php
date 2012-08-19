<?php

/**
 * This is the model class for table "ListFields".
 *
 * The followings are the available columns in table 'ListFields':
 * @property integer $FieldID
 * @property integer $ListID
 * @property integer $IsMultipleSelect
 */
class ListFields extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ListFields the static model class
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
		return 'ListFields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('ListID', 'required', 'on'=>'add'),
			array('FieldID, ListID', 'required', 'on'=>'edit'),
			array('ListID', 'numerical', 'allowEmpty'=>false, 'message'=> Yii::t('fields','Select list') ),
			array('ListID, IsMultipleSelect', 'numerical', 'integerOnly'=>true),
			array('FieldID, ListID, IsMultipleSelect', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'FieldID'			=> Yii::t('fields','FieldID'),
			'ListID'			=> Yii::t('fields','List'),
			'IsMultipleSelect'	=> Yii::t('fields','Multiple Select'),
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

		$criteria->compare('FieldID',$this->FieldID);
		$criteria->compare('ListID',$this->ListID);
		$criteria->compare('IsMultipleSelect',$this->IsMultipleSelect);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    // форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'ListID'=> array(
    		    	'type'  =>  'dropdownlist',
				    'items' =>  CHtml::listData(Lists::model()->findAll(), 'ID', 'Name'),
				    'empty'=>  '',
			    ),
				'IsMultipleSelect'=>array(
    				'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
	}

}