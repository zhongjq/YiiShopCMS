<?php

/**
 * This is the model class for table "Products".
 *
 * The followings are the available columns in table 'Products':
 * @property integer $id
 * @property integer $status
 * @property string $name
 * @property string $alias
 * @property string $title
 * @property string $keywords
 * @property string $description
 *
 * The followings are the available model relations:
 * @property ProductsFields[] $productsFields
 */
class Product extends CActiveRecord
{
    public static $exceptions = array('admin','cart','my','baner','manufacturer');

    public $fields;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias', 'required', 'on'=>'create, edit'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('alias', 'length', 'max'=>50),
			array('name, alias', 'unique'),
			array('title, keywords, description', 'safe'),
			array('alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u', 'message' => Yii::t("products",'Field contains invalid characters.') ),
            array('alias', 'in', 'range'=> self::$exceptions, 'not'=> true, 'message' => Yii::t("products",'Be exceptional ('.implode(', ',self::$exceptions).').') ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, name, alias', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'productFields' => array(self::HAS_MANY, 'ProductField', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => Yii::t('products','Status'),
			'name' => Yii::t('products','Name'),
			'alias' => Yii::t('products','Alias'),
			'title' => Yii::t('products','Title'),
			'keywords' => Yii::t('products','Keywords'),
			'description' => Yii::t('products','Description'),
		);
	}

	public function saveProductsFields( array $ProductsFields ){

		if ( !empty($ProductsFields) ) {
			$FieldsID = array();
			foreach($ProductsFields as $FieldID => $FieldData ){

				if ( stripos($FieldID,"new_") !== false )
					$Field = new ProductsFields();
				elseif ( is_numeric($FieldID) && $FieldID > 0 )
					$Field = ProductsFields::model()->findByPk($FieldID);
				else
					throw new CException("ID NOT NUMERIC");

				$Field->setScenario('add');
				$Field->attributes = $FieldData;
				$Field->ProductID = $this->ID;
				if ( !$Field->save() ) throw new CException("Error");

				$FieldsID[] = $FieldID;
			}

			$DeleteFields = array_diff($this->getIDFields(),$FieldsID);

			if ( !empty($DeleteFields) ){
				ProductsFields::model()->deleteByPk( array_values($DeleteFields) );
			}

		} else {
			ProductsFields::model()->deleteAll('ProductID = :ProductID',array(":ProductID"=>$this->ID));
		}
		return true;
	}

	public function getIDFields(){
		$return = array();
		foreach($this->productsFields() as $Field) {
			$return[] = $Field->ID;
		}
		return $return;
	}

    public function afterFind(){
        $this->setFields();
    }

    public function setFields($order = "position"){

        $connection=Yii::app()->db;

        $sql="
SELECT
`product_field`.*,
`field_tab`.`position` as `position_tab`,
`min_length`,`max_length`,
NULL as `min_value`, NULL as `max_value`,NULL as `rows`,NULL as `decimal`,NULL as `default`,NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- min_length, max_length
JOIN `string_field` ON string_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT
`product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL,NULL,`min_value`,`max_value`,NULL,NULL,NULL,NULL,NULL,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- min_value, max_value
JOIN `integer_field` ON integer_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT
`product_field`.*,
`field_tab`.`position` as `position_tab`,
`min_length`,`max_length`,
NULL as `min_value`, NULL as `max_value`,
`rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- row, min_length, max_length
JOIN `text_field` ON text_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, `max_value`,
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- max_value
JOIN `price_field` ON price_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`,
NULL as `rows`,
`decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- decimal
JOIN `double_field` ON double_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`,
NULL as `rows`,
NULL as `decimal`,
`default`,
NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- default
JOIN `boolean_field` ON boolean_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`,
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
list_id,
is_multiple_select,
`tab_id`,
NULL as `file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- list_id, is_multiple_select
JOIN `list_field` ON list_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`,
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select,
`tab_id`,
`file_type`,
NULL as `manufacturer_id`
FROM `product_field`
-- type_file
JOIN `file_field` ON file_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`,
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
is_multiple_select,
NULL as `tab_id`,
NULL as `file_type`,
`manufacturer_id`
FROM `product_field`
-- manufacturer_field
JOIN `manufacturer_field` ON `manufacturer_field`.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

ORDER BY ".$order  ;


        $command = $connection->cache(1000)->createCommand($sql);
        $command->bindValue(":product_id",$this->id,PDO::PARAM_STR);

        $fields = $command->setFetchMode(PDO::FETCH_OBJ)->queryAll();

		if( !empty($fields) ){
            $this->fields = array();
            foreach ($fields as &$value) {
    			$this->fields[$value->id] = $value;
    		}unset($value);
		}
//        echo"<pre>";
//        print_r($this->fields);
//        die;
    }


	public function beforeDelete(){
		if( parent::beforeDelete() ) {
			Yii::app()->db->createCommand()->dropTable($this->alias);
		}
		return true;
	}

	// форма в формате CForm
	public function getMotelCForm(){
		return new CForm(array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well'
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
				'status'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
				'name'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'alias'=>array(
					'type'      =>  'text',
					'maxlength' =>  255,
					"disabled".$this->isNewRecord  =>  "disabled1",
				),
				'title'=>array(
					'type'=>'textarea','class'=>"span5"
				),
				'keywords'=>array(
					'type'=>'textarea','class'=>"span5"
				),
				'description'=>array(
					'type'=>'textarea','class'=>"span5",'rows'=>5
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

	public function getRecordObject($scenario = "insert"){
		//$record = DynamicActiveRecord::model($this->alias);

        if ( !class_exists($this->alias, false) ) eval("class ".$this->alias." extends CustemCActiveRecord {}");

        $record = new $this->alias($scenario);
        $record->productName = $this->alias;
		$record->product = $this;
		$record->init();

        $record->setScenario($scenario);
        return $record;
	}

	public static function getElementsMenuProduct(){
		$produts = Yii::app()->db->createCommand()->select('id, name')->from('product')->queryAll();

		$items = array();
		if ( $produts ){
			foreach($produts as $produt){
                $produt = (object)$produt;
				$items[] = array(	'label'	=> CHtml::encode($produt->name),
									'url'	=> Yii::app()->createUrl('/admin/product/view',array('id'=>$produt->id)),
									'active'=> ( Yii::app()->controller->id =='product' && Yii::app()->request->getParam('id') == $produt->id )
				);
			}
			$items[] = array('label'=>null,'itemOptions'=>array('class'=>"divider"));
		}
		return $items;
	}


    public function searchByManufacturer($manufacturer_id){

        $product = $this->getRecordObject('search');

    	foreach($this->productFields() as $field) {
        	if( $field->field_type == TypeField::MANUFACTURER )
                $product->{$field->alias} = $manufacturer_id;
        }

		$className = get_class($product);
		if (isset($_GET[$className]))
			$product->attributes = $_GET[$className];

        return $product;
    }

    public function searchByCategory($category_id){

        $product = $this->getRecordObject('search');

    	foreach($this->productFields() as $field) {
        	if( $field->field_type == TypeField::CATEGORIES )
                $product->{$field->alias} = $category_id;
        }

        return $product;
    }

	public static function getProductByPk($id, $select = "id, name, alias" ){
		$product = Yii::app()->db->createCommand()
						->select($select)
						->from('product')
						->where('id=:id', array(':id'=>$id))->queryRow();

		return $product ? (object)$product : null;
	}
}
