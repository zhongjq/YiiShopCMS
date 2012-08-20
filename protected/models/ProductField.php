<?php

/**
 * This is the model class for table "ProductsFields".
 *
 * The followings are the available columns in table 'ProductsFields':
 * @property integer $ID
 * @property integer $ProductID
 * @property integer $FieldType
 * @property string $Name
 * @property integer $IsMandatory
 * @property integer $IsFilter
 *
 * The followings are the available model relations:
 * @property Products $product
 */
class ProductField extends CActiveRecord
{
	public $moredata;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductsFields the static model class
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
		return 'product_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_type, name, alias', 'required', 'on'=>'add, edit'),
			array('product_id, field_type, is_mandatory, is_filter', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('alias', 'length', 'max'=>50),
			array('name, alias', 'unique', 'criteria' => array(
												'condition' => 'product_id = :product_id',
												'params'=>array(':product_id'=> $this->ProductID )
											)),

			array('is_column_table', 'boolean'),
			array('alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u','message' => Yii::t("products",'Field contains invalid characters.')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, field_type, name, is_mandatory, is_filter', 'safe', 'on'=>'search'),
		);
	}


    public function getRelationsNameArray(){
        return array_keys($this->relations());
    }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'Product'       => array(self::BELONGS_TO, 'Product', 'product_id'),
    		
			'IntegerFields' => array(self::HAS_ONE, 'IntegerFields', 'FieldID'),
			'PriceFields'   => array(self::HAS_ONE, 'PriceFields', 'FieldID'),
			'StringFields'  => array(self::HAS_ONE, 'StringFields', 'FieldID'),
			'TextFields'    => array(self::HAS_ONE, 'TextFields', 'FieldID'),
            'ListFields'    => array(self::HAS_ONE, 'ListFields', 'FieldID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID'            =>  Yii::t("fields",'ID'),
			'ProductID'     =>  Yii::t("fields",'Идентификатор продукта'),
			'FieldType'     =>  Yii::t("fields",'Тип поля'),
			'Name'          =>  Yii::t("fields",'Наименование'),
			'Alias'         =>  Yii::t("fields",'Псевдоним'),
			'IsMandatory'   =>  Yii::t("fields",'Обязательно'),
			'IsFilter'      =>  Yii::t("fields",'Использовать в фильтрации'),
			'IsColumnTable' =>  Yii::t("fields",'Used In Table Header?'),
			'UnitName'      =>  Yii::t("fields",'Used In Table Header?'),
			'Hint'          =>  Yii::t("fields",'Used In Table Header?'),
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
		$criteria->compare('ProductID',$this->ProductID);
		$criteria->compare('FieldType',$this->FieldType);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('IsMandatory',$this->IsMandatory);
		$criteria->compare('IsFilter',$this->IsFilter);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// форма в формате CForm
	public function getMotelCForm(){
		return new CForm(array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'FieldForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "FieldForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
				'FieldType'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  TypeFields::getFieldsList(),
					'empty'=>  '',
					"disabled".$this->isNewRecord  =>  "disabled1",
					'ajax' => array(
						'type'  =>  'POST',
						'url'   =>  "",
						'update'=>  '#FieldForm',
					)
				),
				'Name'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'Alias'=>array(
					'type'      =>  'text',
					'maxlength' =>  255,
					"disabled".$this->isNewRecord  =>  "disabled1",
				),
				'IsMandatory'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'IsFilter'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'IsColumnTable'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'Name'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' =>  "btn"
				),
			),
		), $this);
	}

	public static function CreateField($FieldType){
		if ( !isset(TypeFields::$Fields[$FieldType]['class']) ){
			throw new CException("NOT NUMERIC");
		}

		return new TypeFields::$Fields[$FieldType]['class']('add');
	}


	public function afterSave(){
		parent::afterSave();

		if ( $this->moredata ) {
			$this->moredata->FieldID = $this->ID;
			if ( $this->moredata->save() ){
				$Product = Products::model()->findByPk($this->ProductID);

				if ($this->isNewRecord)
					Yii::app()->db->createCommand()->addColumn( $Product->Alias,
						$this->Alias,
						TypeFields::$Fields[$this->FieldType]['dbType']
					);

				return true;
			}
		}
	}

	public function afterDelete(){
		parent::afterDelete();
		$Product = Products::model()->findByPk($this->ProductID);
		Yii::app()->db->createCommand()->dropColumn( $Product->Alias, $this->Alias );
	}
}