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
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Products the static model class
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
			'id'        => 'ID',
			'status'    => Yii::t('products','Status'),
			'name'      => Yii::t('products','Name'),
			'alias'     => Yii::t('products','Alias'),
			'title'     => Yii::t('products','Title'),
			'keywords'      => Yii::t('products','Keywords'),
			'description'   => Yii::t('products','Description'),
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
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Alias',$this->Alias,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Save Fields propucts
	 * @param array $ProductsFields
	 */
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

	public function beforeDelete(){
		if( parent::beforeDelete() ) {
			$Products = $this->findAll();
			if ( $Products )
				foreach($Products as $Product){
					Yii::app()->db->createCommand()->dropTable($Product->Alias);
				}
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

	public function getGoodsObject(){
		eval("class {$this->alias} extends Record{}");
		$Goods = new $this->alias();
		$Goods->setProductID($this->id);
		//$Goods->setGoodsAttributes();
		return $Goods;
	}
    
    public static function getElementsMenuProduct(){
        $produts = Product::model()->findAll();
       
        $items = array();
        if ( $produts ){
            foreach($produts as $produt){
               $items[] = array('label'	=> CHtml::encode($produt->name),
						        'url'	=> Yii::app()->createUrl('/admin/product/view',array('id'=>$produt->id)),
								'active'=> ( Yii::app()->controller->id =='product' && Yii::app()->request->getParam('id') == $produt->id )
                                );
            }
            $items[] = array('itemOptions'=>array('class'=>"divider"));           
            
            
        }
        return $items;
    }
}