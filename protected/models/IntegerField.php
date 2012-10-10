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
class IntegerField extends Field 
{
    public $field_id;
    public $min_value;
    public $max_value;

	public static function tableName()
	{
		return 'integer_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.min_value as '.self::tableName().'_min_value',
                self::tableName().'.max_value as '.self::tableName().'_max_value',
            );
        
        return $return;
    }

	public function rules()
	{
		return array(
			//array('min_value, max_value', 'required', 'on'=>'add'),
			array('field_id', 'required', 'on'=>'edit'),
			array('field_id, min_value, max_value', 'numerical','integerOnly'=>true),
			array('min_value, max_value', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true ),
		);
	}

	public function attributeNames()
	{
		return array(
			'min_value' => Yii::t('fields','Min value'),
			'max_value' => Yii::t('fields','Max value'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->min_value = $params[self::tableName().'_min_value'];
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

    public function findByPk($field_id)
    {
        $row = Yii::app()->db->createCommand()->from($this->tableName())->where('field_id = :field_id', array(':field_id'=>$field_id))->queryRow();
        
        if ( $row ) $this->attributes = $row;
        
        return $this;
    }

    public function save()
    {
        $db = Yii::app()->db->createCommand();
        $db->delete($this->tableName(), 'field_id = :field_id', array(':field_id'=>$field_id));
        
        $db->insert($this->tableName(), array(
            'field_id'=>$this->field_id,
            'min_value'=>$this->min_value,
            'max_value'=>$this->max_value
        ));       
        
        return true;
    }

}