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
												'params'=>array(':product_id'=> $this->product_id )
											)),

			array('is_column_table', 'boolean'),
            array('unit_name, hint', 'safe'),
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
            		'product'=>array(self::BELONGS_TO, 'Product', 'product_id'),

			'integerField' => array(self::HAS_ONE, 'IntegerField', 'field_id'),
			'priceField' => array(self::HAS_ONE, 'PriceField', 'field_id'),
			'stringField' => array(self::HAS_ONE, 'StringField', 'field_id'),
			'textField' => array(self::HAS_ONE, 'TextField', 'field_id'),
            		'listField' => array(self::HAS_ONE, 'ListField', 'field_id'),
            		'categoryField' => array(self::HAS_ONE, 'CategoryField', 'field_id'),
                        'manufacturerField' => array(self::HAS_ONE, 'ManufacturerField', 'field_id'),
            

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t("fields",'ID'),
			'position' => Yii::t("fields",'Приоритет'),
			'product_id' => Yii::t("fields",'Идентификатор продукта'),
			'field_type' => Yii::t("fields",'Тип поля'),
			'name' => Yii::t("fields",'Наименование'),
			'alias' => Yii::t("fields",'Псевдоним'),
			'is_mandatory' => Yii::t("fields",'Обязательно'),
			'is_filter' =>  Yii::t("fields",'Использовать в фильтрации'),
			'is_column_table' => Yii::t("fields",'Used In Table Header?'),
			'unit_name' => Yii::t("fields",'Unitname'),
			'hint' => Yii::t("fields",'Hint'),
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
	public function getMotelArrayCForm(){
		return array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'fieldForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "fieldForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
                'productField'=> array(
    				'type'=>'form',
					'elements'=>array(
						'field_type'=>array(
							'type'  =>  'dropdownlist',
							'items' =>  TypeField::getFieldsList(),
							'empty'=>  '',

							'ajax' => array(
								'type'  =>  'POST',
								'url'   =>  "",
								'replace'=>  '#fieldForm',
							)

						),
						'name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
						'alias'=>array(
							'type'      =>  'text',
							'maxlength' =>  255,
						),
						'is_mandatory'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'is_filter'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'is_column_table'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
    					'unit_name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
    					'hint'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
					)
				)
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' =>  "btn"
				),
			),
		);
	}

	public static function CreateField($FieldType){
		if ( !isset(TypeField::$Fields[$FieldType]['class']) ){
			throw new CException("NOT NUMERIC");
		}

		return new TypeField::$Fields[$FieldType]['class']('add');
	}

	public function afterSave(){
		parent::afterSave();

		if ( $this->moredata ) {
			$this->moredata->field_id = $this->id;
			if ( $this->moredata->save() ){
				if ($this->isNewRecord)
					Yii::app()->db->createCommand()->addColumn( $this->product->alias,
						$this->alias,
						TypeField::$Fields[$this->field_type]['dbType']
					);

				return true;
			}
		}
	}

	public function afterDelete(){
		parent::afterDelete();
		Yii::app()->db->createCommand()->dropColumn( $this->product->alias, $this->alias );
	}
}