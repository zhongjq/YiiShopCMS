<?php

class DynamicActiveRecord
{
    protected static $_instance;

    public $productName;
    public $product;

	private $class;

    public static function model($className,$scenario='insert'){

		if (!class_exists($className, false) )
			eval("class ".$className." extends CustemCActiveRecord {}");

        if (self::$_instance === null) {
            $class = new $className($scenario);

			$gen = new DynamicActiveRecord($class);
			self::$_instance = $gen->getClass();
        }

        return self::$_instance;
    }

    public function __construct($class)
    {
		// if ( !class_exists($className, false) ) eval("class ".$className." extends CActiveRecord {}");
		$this->class = $class;
        $this->productName = $this->class->productName = get_class($class);
		$this->product = $this->class->product = Product::model()->find('alias = :alias', array(':alias'=> $this->productName ));

		if ( $this->product && $this->product->fields ){
            $fields = $this->product->fields;
            foreach( $fields as $field_id => $field ){
				$this->class->attributeLabels[$field->alias] = $field->name;
                $this->addRule($field);
            }
        }
		$this->addRelations();

		$requiredValidator = CValidator::createValidator('default',$this->class,'productName',array('value'=>$this->productName));
        $this->class->getValidatorList()->add($requiredValidator);
    }

	public function getClass(){
		return $this->class;
	}

	protected function addRule($field){
        if ( $field->is_mandatory ) {
            $requiredValidator = CValidator::createValidator('required',$this->class,$field->alias);
            $this->class->getValidatorList()->add($requiredValidator);
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
                $validator = CValidator::createValidator($type,$this->class,$field->alias,$param);
                $this->class->getValidatorList()->add($validator);
            }
        }

        if ( $safe ) {
            $safeValidator = CValidator::createValidator('safe',$this->class,$field->alias);
            $this->class->getValidatorList()->add($safeValidator);
        }

        if ( isset($field->rules) ){
             foreach( $field->rules as $type => $param ){
                $validator = CValidator::createValidator($type,$this->class,$field->alias,$param);
                $this->class->getValidatorList()->add($validator);
            }
        }

    }



	public function getProductID(){
		return $this->product->id;
	}
	public function addRelations()
	{
    	if ( $this->product ){
            foreach( $this->product->fields as $field ){
				$name = $field->alias;
				switch( $field->field_type ){
					case TypeField::LISTS:
                        if ($field->is_multiple_select) {
                            $name = $field->alias;
    						$this->metaData->addRelation($field->alias,array( CActiveRecord::MANY_MANY,
														'ListItem', 'record_list(record_id, list_item_id)',
														'on'=> '`'.$name."_".$name.'`.`product_id` = :product_id',
														'params' => array(":product_id" => $this->getProductID() ),
														'together' => true
													));
						} else
                            $this->class->metaData->addRelation($field->alias,array( CActiveRecord::BELONGS_TO,'ListItem', $field->alias ));
                    break;

					case TypeField::CATEGORIES :
                        if ($field->is_multiple_select)
    						$this->class->metaData->addRelation($field->alias,array(	CActiveRecord::MANY_MANY,
																'Category', 'record_category(record_id, category_id)',
																'on'=> '`'.$name."_".$name.'`.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together' => true
															));
                        else
                            $this->class->metaData->addRelation($field->alias,array( CActiveRecord::BELONGS_TO,'Category', $field->alias ));
                    break;

					case TypeField::MANUFACTURER :
                        if ($field->is_multiple_select)
                            $this->class->metaData->addRelation($field->alias,array(CActiveRecord::MANY_MANY,
																'Manufacturer', 'record_manufacturer(record_id, manufacturer_id)',
																'on'=> '`'.$name."_".$name.'`.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together' => true
														));
                        else
                            $this->class->metaData->addRelation($field->alias,array( CActiveRecord::BELONGS_TO,'Manufacturer', $field->alias, 'select'=> "`{$field->alias}'_manufacturer`.`name`" ));
                    break;
				}
			}
		}
	}




}
