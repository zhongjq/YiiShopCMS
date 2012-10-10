<?php

/**
 * This is the model class for table "TextFields".
 *
 * The followings are the available columns in table 'TextFields':
 * @property integer $field_id
 * @property integer $min_length
 * @property integer $max_length
 * @property integer $rows
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class TextField extends Field
{
    public $field_id;
    public $min_length;
    public $max_length;
    public $rows;
    
	public static function tableName()
	{
		return 'text_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.min_length as '.self::tableName().'_min_length',
                self::tableName().'.max_length as '.self::tableName().'_max_length',
                self::tableName().'.rows as '.self::tableName().'_rows',
            );
        
        return $return;
    }

	public function rules()
	{
		return array(
			array('min_length, max_length, rows', 'required', 'on'=>'add'),
			array('field_id, min_length, max_length, rows', 'required', 'on'=>'edit'),
			array('field_id, min_length, max_length, rows', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeNames()
	{
		return array(
			'min_length' => Yii::t('fields','Min length'),
			'max_length' => Yii::t('fields','Max length'),
			'rows' => Yii::t('fields','Rows'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->min_length = $params[self::tableName().'_min_length'];
        $this->max_length = $params[self::tableName().'_max_length'];
        $this->rows = $params[self::tableName().'_rows'];
    }

	// форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'min_length'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'max_length'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
				'rows'=>array(
					'type'=>'text',
					'maxlength'=>255
				),
			)
		);
	}
}