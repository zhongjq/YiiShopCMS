<?php

/**
 * This is the model class for table "StringFields".
 *
 * The followings are the available columns in table 'StringFields':
 * @property integer $field_id
 * @property integer $min_length
 * @property integer $max_length
 *
 * The followings are the available model relations:
 * @property ProductsFields $field
 */
class StringField extends Field 
{    
	public $field_id;
    public $min_length;
    public $max_length;    

	public static function tableName()
	{
		return 'string_field';
	}
    
    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.min_length as '.self::tableName().'_min_length',
                self::tableName().'.max_length as '.self::tableName().'_max_length',
            );
        
        return $return;
    }
    
	public function rules()
    {
		return array(
			array('min_length, max_length', 'required', 'on'=>'add'),
            array('field_id, min_length, max_length', 'required', 'on'=>'edit'),
            array('field_id', 'numerical', 'integerOnly'=>true),
            array('min_length, max_length', 'numerical', 'integerOnly'=>true, 'min'=>0, 'max'=>255 ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeNames()
	{
		return array(
			'min_length' => Yii::t('fields','Min length'),
			'max_length' => Yii::t('fields','Max length'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->min_length = $params[self::tableName().'_min_length'];
        $this->max_length = $params[self::tableName().'_max_length'];      
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
				)
			);
	}
    public function relations(){return array();}
    public function getDbConnection(){
        return Yii::app()->db;
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
            'min_length'=>$this->min_length,
            'max_length'=>$this->max_length
        ));       
        
        return true;
    }

}