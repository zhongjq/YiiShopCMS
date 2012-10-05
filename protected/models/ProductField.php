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
    public $subClass = null;
    public $subClassName = null;

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
			array('product_id, field_type, position', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('alias', 'length', 'max'=>50),
			array('name, alias', 'unique', 'criteria' => array(
												'condition' => 'product_id = :product_id',
												'params'=>array(':product_id'=> $this->product_id )
											)),

			array('is_filter, is_mandatory, is_column_table, is_editing_table_admin, is_column_table_admin', 'boolean' ),
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
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),

            'booleanField' => array(self::HAS_ONE, 'BooleanField', 'field_id'),
            'categoryField' => array(self::HAS_ONE, 'CategoryField', 'field_id'),
            'dateTimeField' => array(self::HAS_ONE, 'DateTimeField', 'field_id'),
            'doubleField' => array(self::HAS_ONE, 'DoubleField', 'field_id'),
            'imageField' => array(self::HAS_ONE, 'ImageField', 'field_id'),
            'integerField' => array(self::HAS_ONE, 'IntegerField', 'field_id'),
            'listField' => array(self::HAS_ONE, 'ListField', 'field_id'),
            'manufacturerField' => array(self::HAS_ONE, 'ManufacturerField', 'field_id'),
            'priceField' => array(self::HAS_ONE, 'PriceField', 'field_id'),
            'stringField' => array(self::HAS_ONE, 'StringField', 'field_id'),
            'textField' => array(self::HAS_ONE, 'TextField', 'field_id'),

            'tabs' => array(self::HAS_MANY, 'Tab', 'product_id'),
            'fieldTab' => array(self::HAS_ONE, 'FieldTab', 'field_id'),
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
            'is_editing_table_admin'=> Yii::t("fields","Editing table"),
            'is_column_table_admin'=> Yii::t("fields","is_column_table_admin")
		);
	}

	// форма в формате CForm
	public function getCForm(){

        $tab = '<ul class="nav nav-tabs">
                    <li class="active"><a href="#field" data-toggle="tab">Поле</a></li>
                    <li><a href="#admin" data-toggle="tab">Администрирование</a></li>
                </ul>';

    	if ( $this->isNewRecord && $this->field_type ){
			$this->subClassName = TypeField::$Fields[$this->field_type]['class'];
			$this->subClass = $this->CreateField($this->field_type);
		}

		$arForm = array(
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
                $tab,
                '<div class="tab-content">',
                    '<div id="field" class="tab-pane active">',

        				'field_type'=>array(
        					'type' => 'dropdownlist',
        					'items' => TypeField::getFieldsList(),
        					'empty'=> '',
        					'ajax' => array(
                                'url' => "",
        						'type' => 'POST',
        						'replace'=>  '#fieldForm',
        					),
                            'disabled' => !$this->isNewRecord ? true : false,
        				),
        				'name'=>array(
        					'type'=>'text',
        					'maxlength'=>255
        				),
        				'alias'=>array(
        					'type' => 'text',
        					'maxlength' => 255,
                            'disabled' => !$this->isNewRecord ? true : false,
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

                        $this->subClassName => ( $this->subClass ? $this->subClass->getElementsMotelCForm() : null ),
                    '</div>',

                    '<div id="admin" class="tab-pane">',
                        'is_editing_table_admin'=>array(
                    		'type'=>'checkbox',
        					'layout'=>'{input}{label}{error}{hint}',
        				),
                        'is_column_table_admin'=>array(
            				'type'=>'checkbox',
        					'layout'=>'{input}{label}{error}{hint}',
        				),
                    '</div>',

                '</div>'
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? Yii::t('main','Create') : Yii::t('main','Save'),
					'class' =>  "btn"
				),
			),
		);

        $form = new CForm($arForm,$this);

        if ( $this->subClass ) $form[$this->subClassName]->model = $this->subClass;

        return $form;
	}

	public static function CreateField($FieldType){
		if ( !isset(TypeField::$Fields[$FieldType]['class']) ){
			throw new CException("NOT NUMERIC");
		}

		return new TypeField::$Fields[$FieldType]['class']('add');
	}

	public function afterSave(){
		parent::afterSave();

		${$this->product->alias} = $this->product->getRecordObject();

		if ( $this->subClass ) {

            // чтобы сохранять значение
            if( $this->subClass && isset($_POST[$this->subClassName]) )
                $this->subClass->attributes = $_POST[$this->subClassName];

			$this->subClass->field_id = $this->id;
			if ( $this->subClass->save() ){

				if( isset($this->subClass->is_multiple_select) ){
					if ($this->isNewRecord && $this->subClass->is_multiple_select == 0){
						Yii::app()->db->createCommand()->addColumn( $this->product->alias,
							$this->alias,
							TypeField::$Fields[$this->field_type]['dbType']
						);
					} elseif ( $this->subClass->is_multiple_select && isset(${$this->product->alias}->tableSchema->columns[$this->alias]) ){
						Yii::app()->db->createCommand()->dropColumn( $this->product->alias, $this->alias );
					} elseif ( !$this->subClass->is_multiple_select && !isset(${$this->product->alias}->tableSchema->columns[$this->alias]) ){
						Yii::app()->db->createCommand()->addColumn( $this->product->alias,$this->alias,TypeField::$Fields[$this->field_type]['dbType']);
					}

				} elseif( !isset(${$this->product->alias}->tableSchema->columns[$this->alias]) ) {
					Yii::app()->db->createCommand()->addColumn( $this->product->alias,
						$this->alias,
						TypeField::$Fields[$this->field_type]['dbType']
					);
				}

				return true;
			}
		}
	}

	public function afterDelete(){
		parent::afterDelete();
		Yii::app()->db->createCommand()->dropColumn( $this->product->alias, $this->alias );
	}

    public function afterFind(){
        parent::afterFind();

        if ( $this->field_type ){
			$this->subClassName = TypeField::$Fields[$this->field_type]['class'];
			$class = $this->CreateField($this->field_type);
            $this->subClass = $class::model()->findByPk($this->id);
		}
    }
}
