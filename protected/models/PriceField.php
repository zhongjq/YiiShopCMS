<?php

/**
 * This is the model class for table "IntegerFields".
 *
 * The followings are the available columns in table 'IntegerFields':
 * @property integer $field_id
 * @property integer $MinLength
 * @property integer $MaxLength
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class PriceField extends Field
{
    public $field_id;
    public $min_value;
    public $max_value;
    
	public static function tableName()
	{
		return 'price_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.max_value as '.self::tableName().'_max_value',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('field_id', 'required', 'on'=>'edit'),
			array('field_id, max_value', 'numerical', 'integerOnly'=>true),
			array('max_value', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
		);
	}

	public function attributeNames()
	{
		return array(
			'max_value' => Yii::t('fields','Max value'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->max_value = $params[self::tableName().'_max_value'];      
    }

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'min_value'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
				'max_value'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}

}