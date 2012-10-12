<?php

class DynamicModel extends CModel
{
    protected static $_instance;
    
    public $productName;
    public $product;
    public $isNewRecord = false;
    
    private $primaryKey = array('id');
    private $join = array();
    private $defaultFields = array(
        array('alias'=>'id','rules'=>array('numerical'=>array('min'=>1) )),
        array('alias'=>'title','name'=>"Заголовок",'is_mandatory'=>false,'field_type'=>TypeField::STRING,'max'=>300, 'allowEmpty'=>true),
        array('alias'=>'keywords','name'=>"Ключевые слова",'is_mandatory'=>false,'field_type'=>TypeField::STRING,'max'=>200, 'allowEmpty'=>true),
        array('alias'=>'description','name'=>"Описание",'is_mandatory'=>false,'field_type'=>TypeField::TEXT,'max'=>400, 'allowEmpty'=>true),
        array('alias'=>'alias','name'=>"Алиас",'is_mandatory'=>false,'field_type'=>TypeField::STRING,'max'=>255,'allowEmpty'=>true,'rules'=>array('unique'=>array())),
    );
    
    private $_attributeNames = array();
    private $_attributeLabels = array();
    
    public static function model($className){
        if (self::$_instance === null) {
            self::$_instance = new self($className);   
        }
        self::$_instance->isNewRecord = false;
        return self::$_instance;
    }
    
    public function __set($key,$value){$this->$key = $value;}
    public function __get($value){return $value;} 
    
    public function __construct($className){
        
        // поля по умолчанию
        foreach( $this->defaultFields as $field_id => $arField ){
            $field = (object)$arField;
            $this->{$field->alias} = null; // добавляем своисво
            $this->_attributeNames[] = $field->alias; // добавляем имя
            $this->_attributeLabels[$field->alias] = $field->name; // добавляем имя
            $this->addRule($field);
        }
        
        $this->isNewRecord = true;
        
        $this->productName = $className;
        $this->product = Product::model()->find('alias = :alias', array(':alias'=> $this->productName ));
        
        if ( $this->product && $this->product->fields ){
            $fields = $this->product->fields;
            foreach( $fields as $field_id => $field ){
                $this->{$field->alias} = null; // добавляем своисво
                $this->_attributeNames[] = $field->alias; // добавляем имя
                $this->_attributeLabels[$field->alias] = $field->name; // добавляем имя
                $this->addRule($field);   
                $this->addJoin($field);
            }
        }       
        
    }
    
    public function tableName(){return $this->productName;}
    public function getDbConnection(){return Yii::app()->db;}
    
    protected function addRule($field){
        if ( $field->is_mandatory ) {
            $requiredValidator = CValidator::createValidator('required',$this,$field->alias);
            $this->getValidatorList()->add($requiredValidator);   
        }
        
        $safe = false;
        
        $types = array();
        $params = array();
        
        switch( $field->field_type ){
            case TypeField::STRING :
    		case TypeField::TEXT :
				$safe = true;
                $types[] = 'length';
				$params['length'] = array('min'=> $field->min_length,'max'=>$field->max_length, 'allowEmpty'=>true );
			break;
            
			case TypeField::INTEGER :
                $types[] = 'numerical';
				$params['numerical'] = array('integerOnly'=>true,'min'=> $field->min_value ,'max'=>$field->max_value ,'allowEmpty'=>true );
			break;
            
    		case TypeField::DOUBLE :
				$types[] = 'numerical';

                if ( $field->decimal ){
                    $types[] = 'match';
            	    $params['match'] = array('pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,'.$field->decimal.'}?\s*$/','message' => Yii::t("fields",'Price has the wrong format (eg 10.50).'));
                }

			break;
			
            case TypeField::PRICE:
                $types[] = 'match';
                $params['match'] = array('pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,2}?\s*$/', 'message' => Yii::t("products",'Price has the wrong format (eg 10.50).') );
				
                $types[] = 'numerical';
                $price = array('allowEmpty'=>$field->is_mandatory);
				if ( $field->max_value ) $price['max'] = $field->max_value;
				$params['numerical'] = $price;

			break;
    		
            case TypeField::LISTS :
				if ($field->is_multiple_select){
                    $types[] = 'ArrayValidator';
					$params['ArrayValidator'] = array('validator'=>'numerical', 'params'=>array('integerOnly'=>true, 'allowEmpty'=>false));
				} else {
    			    $types[] = 'numerical';                    
    			    $params['numerical'] = array('integerOnly'=>true,'allowEmpty'=>true);
				}
			break;
    			
            case TypeField::CATEGORIES :
				if ($field->is_multiple_select){
                    $types[] = 'ArrayValidator';
    				$params['ArrayValidator'] = array('validator'=>'numerical', 'params'=>array('integerOnly'=>true));					
				} else {
        		    $types[] = 'numerical';                    
    			    $params['numerical'] = array('integerOnly'=>true,'allowEmpty'=>true);
				}
			break;
    		
            case TypeField::IMAGE :
                $types[] = 'ArrayValidator';
        		$params['ArrayValidator'] = array('validator'=>'file', 'params'=>array('types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>false));
			break;
    		
            case TypeField::BOOLEAN :
                $types[] = 'boolean';
				$params['boolean'] = array('falseValue'=> 0, 'trueValue' => 1, 'allowEmpty'=> true );
                
                if ( $field->default ){
                    $types[] = 'default';
                    $params['default'] = array('value'=> $field->default );
                }                            
                
			break;
        	
		}
        
        if ( !empty($types) ){
            foreach( $types as $type ){
                $param = isset($params[$type]) ? $params[$type] : array();
                $validator = CValidator::createValidator($type,$this,$field->alias,$param);
                $this->getValidatorList()->add($validator);   
            }                       
        }
        
        if ( $safe ) {
            $safeValidator = CValidator::createValidator('safe',$this,$field->alias);
            $this->getValidatorList()->add($safeValidator);  
        }
        
        if ( isset($field->rules) ){
             foreach( $field->rules as $type => $param ){
                $validator = CValidator::createValidator($type,$this,$field->alias,$param);
                $this->getValidatorList()->add($validator);   
            }            
        }
        
    }

    protected function addJoin($field){
    	switch( $field->field_type ){
			case TypeField::LISTS:
                if ($field->is_multiple_select) {
                    $this->join[$field->alias] = array();
				} else
                    $this->join[$field->alias] = array();
            break;
                    
			case TypeField::CATEGORIES :
                if ($field->is_multiple_select)
    		    	$this->join[$field->alias] = array();
                else
                    $this->join[$field->alias] = array();
            break;

			case TypeField::MANUFACTURER :
                if ($field->is_multiple_select)
                    $this->join[$field->alias] = array();
                else
                    $this->join[$field->alias] = array();
            break;
		}       
    }
    
    protected function getJoinName(){
        return array_keys($this->join);      
    }   


	protected function isEmpty($value,$trim=false){
		return $value===null || $value===array() || $value==='' || $trim && is_scalar($value) && trim($value)==='';
	}
    
    public function unique($attribute,$params){
        
    	$value= $this->$attribute;
		if($this->allowEmpty && $this->isEmpty($value)) return;        
        
        $query = $attribute.'=:'.$attribute;
        $params = array(
            ':'.$attribute => $this->{$attribute},
        );
        
        if ( $this->id ) {
            $query = ' AND id != :id ';
            $params[':id'] = $this->id;
        }
        
        $exists = Yii::app()->db->createCommand()
                        ->select('COUNT(*) as c')
                        ->from($this->tableName())
                        ->where($query,$params)
                        ->queryScalar();

        if($exists == 0){;
    		$this->addError($attribute,CHtml::encode("{$attribute} \"{$value}\" has already been taken."));
    	}   
    }

    public function attributeNames(){return $this->_attributeNames;}
    public function attributeLabels(){return $this->_attributeLabels;}
    
    
    public function findByPk($id){
        $row = Yii::app()->db->createCommand()->from($this->tableName())->where('id=:id',array(":id"=>$id))->queryRow();
        if ( !empty($row) ) $this->setAttributes($row);
        return $this;
    }
    
    public function save($validate = true){
        
        if ( $validate && !$this->validate() ) {
            return false;
        } 
        
        $value = $this->getAttributes();
        unset( $value['id'] );
        
        // удаляем связи
        if ( $this->getJoinName()  ) foreach( $this->getJoinName() as $name ) unset( $value[$name] );
        
        
        $db = Yii::app()->db;        
        $transaction=$db->beginTransaction();
        try
        {
            if ( $this->isNewRecord ){            
                $db->createCommand()->insert($this->tableName(), $value );
            } else {
                $db->createCommand()->update($this->tableName(), $value, 'id=:id', array(':id'=> $this->id ) );
            }
        
            $transaction->commit();
        }
        catch(Exception $e)
        {            
            $transaction->rollback();
        }
        
    }
    
}