<?php

/**
 * This is the model class for table "category_field".
 *
 * The followings are the available columns in table 'category_field':
 * @property integer $field_id
 * @property integer $category_id
 *
 * The followings are the available model relations:
 * @property ProductField $field
 */
class CategoryField extends Field
{
    public $field_id;
    public $category_id;
    public $is_multiple_select;
    
	public static function tableName()
	{
		return 'category_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.category_id as '.self::tableName().'_category_id',
                self::tableName().'.is_multiple_select as '.self::tableName().'_is_multiple_select',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('category_id', 'required', 'on'=>'add'),
            array('field_id, category_id', 'required', 'on'=>'edit'),
			array('field_id, category_id, is_multiple_select', 'numerical', 'integerOnly'=>true),
		);
	}

	public function attributeNames()
	{
		return array(
			'category_id' =>  Yii::t('fields',"Category"),
            'is_multiple_select'=> Yii::t('fields',"Is Multiple Select?")
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->category_id = $params[self::tableName().'_category_id'];
        $this->is_multiple_select = $params[self::tableName().'_is_multiple_select'];      
    }

    // форма в формате CForm
    public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'category_id'=> array(
    		    	'type'  =>  'dropdownlist',
				    'items' =>  CHtml::listData(Category::model()->findAll(), 'id', 'name'),
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