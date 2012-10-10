<?php

/**
 * This is the model class for table "image_field".
 *
 * The followings are the available columns in table 'image_field':
 * @property integer $field_id
 * @property integer $is_multiple_select
 * @property integer $quantity
 *
 * The followings are the available model relations:
 * @property ProductField $field
 * @property ImageFieldParameter[] $imageFieldParameters
 */
class ImageField extends Field 
{
    public $field_id;
    public $quantity;
    
	public static function tableName()
	{
		return 'image_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.quantity as '.self::tableName().'_quantity',
            );
        
        return $return;
    }
    
	public function rules(){
		return array(
			array('field_id', 'required', 'on'=>'edit'),
			array('field_id, quantity', 'numerical', 'integerOnly'=>true),
			array('quantity', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
		);
	}

	public function attributeNames()
	{
		return array(
			'quantity' => 'Quantity',
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->quantity = $params[self::tableName().'_quantity'];      
    }

    public function getElementCForm(){
	    return array(
    		'type' => 'Files',
            'accept'=>'jpg|gif|png',
        );
    }

	// форма в формате CForm
	public function getElementsMotelCForm(){        
		return array(
				'type'=>'form',
				'elements'=>array(
					'quantity'=>array(
						'type'=>'text',
						'maxlength'=>255
					),
				)
			);
	}
}