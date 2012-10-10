<?php

/**
 * This is the model class for table "double_field".
 *
 * The followings are the available columns in table 'double_field':
 * @property string $field_id
 * @property string $decimal
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class DoubleField extends Field
{
    public $field_id;
    public $decimal;
    
	public static function tableName()
	{
		return 'double_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.decimal as '.self::tableName().'_decimal',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('field_id', 'required'),
			array('field_id, decimal', 'length', 'max'=>11),
		);
	}

	public function attributeNames()
	{
		return array(
			'decimal' => Yii::t('fields','Количество знаков после запятой'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->decimal = $params[self::tableName().'_decimal'];      
    }
    
	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'decimal'=>array(
					'type'=>'text',
					'maxlength'=>11
				),
			)
		);
	}
}