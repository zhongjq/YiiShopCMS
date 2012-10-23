<?php

class DynamicModel extends CModel
{
    protected static $_instance;
    
    const BELONGS_TO = 1;
    const MANY_MANY = 2;
    
    public $productName;
    public $product;
    public $isNewRecord = false;

    private $primaryKey = array('id');
    private $join = array();
    private $defaultFields = array(
        array('alias'=>'id','name'=>"id",'is_mandatory'=>false,'field_type'=>TypeField::INTEGER,'rules'=>array('numerical'=>array('min'=>1) )),
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
    public function tableAlias(){return $this->tableName()." t";}
    public function getDbConnection(){return Yii::app()->db;}
    public function getProductID(){return $this->product->id;}
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
				$params['length'] = array(
												'min'=> isset($field->min_length) ? $field->min_length : null ,
												'max'=> isset($field->max_length) ? $field->max_length : null ,
												'allowEmpty'=>true );
			break;

			case TypeField::INTEGER :
                $types[] = 'numerical';
				$params['numerical'] = array(	'integerOnly'=>true,
												'min'=> isset($field->min_value) ? $field->min_value : null ,
												'max'=> isset($field->max_value) ? $field->max_value : null ,
												'allowEmpty'=>true );
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
                    $this->join[$field->alias] = array( self::MANY_MANY,
                                                        'tableName' => 'list_item',
                                                        'on' => 'list_item.id = record_list.list_item_id',
                                                        'tableAliasFields'=> array('list_item_id'=>'id', 'list_item_name'=>'name'),
                                                        'select'=> array('list_item.id as list_item_id', 'list_item.name as list_item_name'),
                                                        'class'=> 'ListItem',
                                                        'relation' => array(
                                                            'tableName' => 'record_list',
                                                            'on'=>"( record_list.record_id = t.id AND record_list.product_id = {$this->getProductID()} )"
                                                            )
                                                        );
				} else
                    $this->join[$field->alias] = array( self::BELONGS_TO, 
                                                        'tableName' => 'list_item',
                                                        'on' => 'list_item.id = t.'.$field->alias,
                                                        'tableAliasFields'=> array('list_item_id'=>'id', 'list_item_name'=>'name'),
                                                        'select'=> array('list_item.id as list_item_id', 'list_item.name as list_item_name'),
                                                        'class'=> 'ListItem'
                                                        );
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
            $query .= ' AND id != :id ';
            $params[':id'] = $this->id;
        }

        $exists = Yii::app()->db->createCommand()
                        ->select('COUNT(*) as c')
                        ->from($this->tableName())
                        ->where($query,$params)
                        ->queryScalar();

        if($exists){;
    		$this->addError($attribute,CHtml::encode("{$attribute} \"{$value}\" has already been taken."));
    	}
    }

    public function attributeNames(){return $this->_attributeNames;}
    public function attributeLabels(){return $this->_attributeLabels;}


    public function find($conditions, $params=array()){
        $row = Yii::app()->db->createCommand()->from($this->tableName())->where($conditions,$params)->queryRow();
        if ( !empty($row) ) {
			$this->setAttributes($row);
		}
		$this->isNewRecord = false;
        return $this;
    }

    public function findByPk($id,$conditions, $params=array()){
        $row = Yii::app()->db->createCommand()->from($this->tableName())->where('id=:id',array(":id"=>$id))->queryRow();
        if ( !empty($row) ) {
			$this->setAttributes($row);
		}
		$this->isNewRecord = false;
        return $this;
    }

    public function findAll($conditions='', $params=array(), $query = array()){
        $rows = Yii::app()->db->createCommand()->from($this->tableName().' t');

        
        $select = array('t.*');       
        
        if( !empty($this->join) )
            foreach( $this->join as $name => $join ) {
                switch( $join[0] ){
    		        case self::BELONGS_TO :                        
                        $rows = $rows->leftJoin( $join['tableName'] , $join['on'] );
                    break;
        	        case self::MANY_MANY :
                        $rows = $rows->leftJoin( $join['relation']['tableName'] , $join['relation']['on'] );
                        $rows = $rows->leftJoin( $join['tableName'] , $join['on'] );
                    break;                    
                }  
                
                if ( isset( $join['select'] )  ) $select = array_merge($select,$join['select']);                
            }
        
        if ( isset($query['limit']) ){
            $rows = $rows->limit($query['limit'], isset($query['offset']) ? $query['offset'] : null );
        }
        
        $rows = $rows->select($select)->where($conditions,$params)->queryAll();

		$return = array();
		if ( is_array($rows) && !empty($rows) ) {
        

            $tmp = array();
            foreach ($rows as $row){
                $join = $this->join;
                
                if ( !empty($join) ){
                    foreach ($join as $name => $j){
                        $row[$name] = array();
                        switch( $j[0] ){
    			            case self::BELONGS_TO :                                
                                foreach ($j['tableAliasFields'] as $k => $fieldAlias){
                                    $row[$name][$fieldAlias] = $row[$k];
                                    unset($row[$k]);
                                }                               
                            break;
        		            case self::MANY_MANY :
                                $tmpList = array();
                                
                                foreach ($j['tableAliasFields'] as $k => $fieldAlias){
                                    if( !empty($row[$k]) ) $tmpList[$fieldAlias] = $row[$k];
                                    unset($row[$k]);
                                }
                                
                                if ( isset($tmp[$row['id']]) ){
                                    $row[$name][$row['id']] = array_merge($tmpList,$row[$name]) ;
                                    $row[$name] = array_merge($tmp[$row['id']][$name],$row[$name]) ;
                                } else {
                                    $row[$name][$row['id']] = array_merge($tmpList,$row[$name]) ;
                                }                                
                                
                            break;                        
                        }
                    }
                   
                }
                
                $tmp[$row['id']] = $row;
			}
            unset($row);
            $rows = $tmp;            
            
			foreach ($rows as $row) {
				$obj = clone $this;                
				$obj->setAttributes($row);
				$return[] = $obj;
			}
			unset($obj);
		}
		$this->isNewRecord = false;
        return $return;
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
            if ( $this->isNewRecord && $this->id == null ){
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

    public function search()
	{
        $pageSize = 20;
        $page = 1;
        if ( isset($_GET['page']) && is_numeric($_GET['page']) ) 
            $page = ($_GET['page'] - 1) * $pageSize;
        
        
		return  new CArrayDataProvider($this->findAll('',array(),array('limit'=>$pageSize,'offset'=>$page)), array(
            'pagination'=>array(
                'pageSize'=>$pageSize,
                'pageVar'=>'page',                
            ),
           'totalItemCount'=> Yii::app()->db->createCommand()->from($this->tableName())->select('COUNT(*)')->queryScalar(),
            
            //'itemCount'=>20
            //'sort'=>array('attributes'=>array('id', 'name'))
            )
        );
	}

    public function getTableFields(){
        $fields = array();
        if ( $this->product ){
            foreach( $this->product->fields as $field ){
    			if( $field->is_column_table ){
					$f['name'] = $field->alias;
					//$f['header'] = $field->name;
                    
                    switch( $field->field_type ){
            			case TypeField::LISTS:
                            if ($field->is_multiple_select) {
                                $f['value'] = '$data->getLists('.$field->alias.')';
            				} else {
                                $f['value'] = '$data->'.$field->alias.'["name"]';
            				}
                        break;
                		case TypeField::CATEGORIES:
                            if ($field->is_multiple_select) {
                                $f['value'] = "";
            				} else {
                                $f['value'] = '$data->'.$field->alias.'["name"]';
            				}
                        break;
                		case TypeField::MANUFACTURER:
                            if ($field->is_multiple_select) {
                                $f['value'] = "";
            				} else {
                                $f['value'] = '$data->'.$field->alias.'["name"]';
            				}
                        break;                        
            		}                    
                    
                    $fields[] = $f;
    			}
            }
        }
        return $fields;
    }    

    public function getLists($name, $sSep = ', ')
    {
        if ( empty($this->{$name}) ) return;

        $aRes = array();
        foreach ($this->{$name} as $item) {
            $aRes[] = $item['name'];
        }

        return implode($sSep, $aRes);
    }

}