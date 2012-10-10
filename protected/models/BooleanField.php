<?php

/**
 * This is the model class for table "boolean_field".
 *
 * The followings are the available columns in table 'boolean_field':
 * @property string $field_id
 * @property integer $default
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class BooleanField extends Field
{
    public $field_id;
    public $default;
    
	public static function tableName()
	{
		return 'boolean_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.default as '.self::tableName().'_default',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('field_id', 'required','on'=>'edit'),
			array('field_id, default', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeNames()
	{
		return array(
			'default' => Yii::t('field','Default'),
		);
	}

	public static function getValues(){
		return array( 1 => Yii::t("main","Yes"), 0 => Yii::t("main","No") );
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->default = $params[self::tableName().'_default'];    
    }

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
    			'default'=> array(
    		    	'type' => 'dropdownlist',
				    'items' => self::getValues(),
			    ),                
			)
		);
	}
}