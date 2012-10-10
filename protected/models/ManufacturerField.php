<?php

/**
 * This is the model class for table "manufacturer_field".
 *
 * The followings are the available columns in table 'manufacturer_field':
 * @property string $field_id
 * @property string $manufacturer_id
 * @property integer $is_multiple_select
 *
 * The followings are the available model relations:
 * @property Manufacturer $manufacturer
 * @property ProductField $field
 */
class ManufacturerField extends Field
{
    public $field_id;
    public $manufacturer_id;
    public $is_multiple_select;
    
	public static function tableName()
	{
		return 'manufacturer_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.manufacturer_id as '.self::tableName().'_manufacturer_id',
                self::tableName().'.is_multiple_select as '.self::tableName().'_is_multiple_select',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('field_id', 'required','on'=>'edit'),
			array('is_multiple_select, manufacturer_id', 'numerical', 'integerOnly'=>true),
			array('field_id', 'length', 'max'=>11),
		);
	}

	public function attributeNames()
	{
		return array(
			'manufacturer_id'=>Yii::t('manufacturer',"manufacturer"),
			'is_multiple_select' => 'Is Multiple Select',
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->manufacturer_id = $params[self::tableName().'_manufacturer_id'];
        $this->is_multiple_select = $params[self::tableName().'_is_multiple_select'];      
    }

    // форма в формате CForm
    public function getElementsMotelCForm(){
    	return array(
			'type'=>'form',
			'elements'=>array(
				'manufacturer_id'=> array(
    		    	'type'  =>  'dropdownlist',
				    'items' =>  CHtml::listData(Manufacturer::model()->findAll(), 'id', 'name'),
				    'empty'=>  '',
			    ),
				'is_multiple_select'=>array(
					'type'=>'checkbox',
					'layout'=>'{input}{label}{error}{hint}',
				),
			)
		);
    }
}