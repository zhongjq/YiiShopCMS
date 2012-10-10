<?php

/**
 * This is the model class for table "ListFields".
 *
 * The followings are the available columns in table 'ListFields':
 * @property integer $FieldID
 * @property integer $ListID
 * @property integer $IsMultipleSelect
 */
class ListField extends Field
{
    public $field_id;
    public $list_id;
    public $is_multiple_select;
    
	public static function tableName()
	{
		return 'list_field';
	}

    public static function selectCol(){
        $return = array( 
                self::tableName().'.field_id as '.self::tableName().'_field_id',
                self::tableName().'.list_id as '.self::tableName().'_list_id',
                self::tableName().'.is_multiple_select as '.self::tableName().'_is_multiple_select',
            );
        
        return $return;
    }
    
	public function rules()
	{
		return array(
			array('list_id', 'required', 'on'=>'add'),
			array('field_id, list_id', 'required', 'on'=>'edit'),
			array('list_id', 'numerical', 'allowEmpty'=>false, 'message'=> Yii::t('fields','Select list') ),
			array('list_id, is_multiple_select', 'numerical', 'integerOnly'=>true),
			array('field_id, list_id, is_multiple_select', 'safe', 'on'=>'search'),
		);
	}

	public function attributeNames()
	{
		return array(
			'list_id' => Yii::t('fields','List'),
			'is_multiple_select' => Yii::t('fields','Multiple Select'),
		);
	}

    protected function setAttr($params){
        parent::setAttr($params);
        
        $this->field_id = $params[self::tableName().'_field_id'];
        $this->list_id = $params[self::tableName().'_list_id'];
        $this->is_multiple_select = $params[self::tableName().'_is_multiple_select'];      
    }

    // форма в формате CForm
	public function getElementsMotelCForm(){
		return array(
			'type'=>'form',
			'elements'=>array(
				'list_id'=> array(
    		    	'type'  =>  'dropdownlist',
				    'items' =>  CHtml::listData(Lists::model()->findAll(), 'id', 'name'),
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