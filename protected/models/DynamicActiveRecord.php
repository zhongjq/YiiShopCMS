<?php

class DynamicActiveRecord extends CActiveRecord
{
    
    private $_manufacturerFilter = null;
	private $_categoryFilter = null;
    private $_productFieldsOrder = null;    
    
    public $productName = null;
    private $_product;
    
    public static function table($tableName){
        eval("class ".$tableName." extends DynamicActiveRecord{}");
        return new $tableName();
    }
    
    public static function model($className){
        eval("class ".$className." extends DynamicActiveRecord{}");
        $a = parent::model($className);
        $a->productName = $className; 
        $a->setProduct();
        $a->addRelations();
        return $a;
    }    

    public function __construct($scenario='insert')
    {
        $this->productName = get_class($this);
        $this->setProduct();
        parent::__construct($scenario);
    }

    private function setProduct()
    {        
        $name = $this->productName."ProductСache";

        if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];        
        
        
        Yii::app()->params[$name] = $this->_product = Product::model()->find('alias = :alias',array(':alias'=> $this->productName ));

    }

    public function getProductID()
	{
		return $this->_product->id;
	}

    public function rules()
	{
		$rules      = array();
		$required   = array();
		$numerical  = array();
		$safe       = array('title','keywords','description');
		$unique     = array("alias");
        
        $this->setProduct();
                
		if ( $this->_product ){
            foreach( $this->_product->fields as $field ){
				if ( $field->is_mandatory ) $required[] = $field->alias;

				switch( $field->field_type ){
					case TypeField::TEXT :
						$safe[] = $field->alias;
						$rules[] = array($field->alias,'length','min'=> $field->min_length,'max'=>$field->max_length,'allowEmpty'=>true );
					break;
					case TypeField::STRING :
						$safe[] = $field->alias;
						$rules[] = array($field->alias,'length','min'=> $field->min_length,'max'=>$field->max_length,'allowEmpty'=>true );
					break;
					case TypeField::INTEGER :
						$rules[] = array($field->alias, 'numerical', 'integerOnly'=>true,'min'=> $field->min_value ,'max'=>$field->max_value ,'allowEmpty'=>true);
					break;
    				case TypeField::DOUBLE :
						$rules[] = array($field->alias, 'numerical', );

                        if ( $field->decimal ){
            				$rules[] = array($field->alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,'.$field->decimal.'}?\s*$/',
    											'message' => Yii::t("fields",'Price has the wrong format (eg 10.50).')
    										);
                        }

					break;
					case TypeField::PRICE:

						$rules[] = array($field->alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,2}?\s*$/',
											'message' => Yii::t("products",'Price has the wrong format (eg 10.50).')
										);
						$price = array($field->alias, 'numerical', 'allowEmpty'=>$field->is_mandatory);

						if ( $field->max_value ) $price['max'] = $field->max_value;
						$rules[] = $price;

					break;
    				case TypeField::LISTS :
						if ($field->is_multiple_select)
							$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true, 'allowEmpty'=>false
											));
						else
							$rules[] = array($field->alias, 'numerical', 'integerOnly'=>true,'allowEmpty'=>true);
					break;
    				case TypeField::CATEGORIES :
						if ($field->is_multiple_select)
							$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array('integerOnly'=>true));
						else
							$rules[] = array($field->alias, 'numerical','integerOnly'=>true );
					break;
    				case TypeField::IMAGE :
						$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'file', 'params'=>array(
											'types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>false
										));

					break;
    				case TypeField::BOOLEAN :
						$rules[] = array($field->alias, 'boolean', 'falseValue'=> 0, 'trueValue' => 1, 'allowEmpty'=> true );
                        if ( $field->default )
                            $rules[] = array($field->alias, 'default', 'value'=> $field->default );
					break;
        			case TypeField::DATETIME :
						if ( $field->is_multiple_select )
							$rules[] = array($field->alias, 'type', 'type' => 'string');
						else
							$rules[] = array($field->alias, 'date', 'format'=> DateTimeField::getFormatLocale($field->type),'allowEmpty'=> $field->is_mandatory );
					break;
				}

			}
		}

		if ( !empty($required) )
			$rules[] = array(implode(',',$required), 'required');

		if ( !empty($safe) )
			$rules[] = array(implode(',',$safe), 'safe');

		if ( !empty($unique) )
			$rules[] = array(implode(',',$unique), 'unique');

		$rules[] = array('alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u',
						'message' => Yii::t("products",'Field contains invalid characters.'));


		return $rules;
	}

    public function attributeLabels()
	{
		$labels = array();

        if ( $this->_product ){
            foreach( $this->_product->fields as $field ){
				$labels[$field->alias] = $field->name;
			}
		}

		return $labels;
	}

    public function addRelations()
	{
    	if ( $this->_product ){
            foreach( $this->_product->fields as $field ){
				switch( $field->field_type ){
					case TypeField::LISTS:
                        if ($field->is_multiple_select) {
                            $name = $field->alias;
    						$this->metaData->addRelation($field->alias,array( self::MANY_MANY,
														'ListItem', 'record_list(record_id, list_item_id)',
                                                        'association'=> array('product'),
                                                        //'select'=> "`{$field->alias}'`.`name`",
														//'condition'=> '`'.$name."_".$name.'`.`product_id` = :product_id',
														//'params' => array(":product_id" => $this->getProductID() ),
														'together' => true
													));
						} else
                            $this->metaData->addRelation($field->alias,array( self::BELONGS_TO,'ListItem', $field->alias ));
                    break;
                    
					case TypeField::CATEGORIES :
                        if ($field->is_multiple_select)
    						$this->metaData->addRelation($field->alias,array(	self::MANY_MANY,
																'Category', 'record_category(record_id, category_id)',
                                                                'select'=> "`{$field->alias}'_category`.`name`",
																'condition'=> $field->alias.'_category.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together' => true
															));
                        else
                            $this->metaData->addRelation($field->alias,array( self::BELONGS_TO,'Category', $field->alias, 'select'=> "`{$field->alias}'_category`.`name`" ));
                    break;

					case TypeField::MANUFACTURER :
                        if ($field->is_multiple_select)
                            $this->metaData->addRelation($field->alias,array(self::MANY_MANY,
																'Manufacturer', 'record_manufacturer(record_id, manufacturer_id)',
                                                                'select'=> "`{$field->alias}'_manufacturer`.`name`",
																'condition'=> $field->alias.'_manufacturer.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together' => true
														));
                        else
                            $this->metaData->addRelation($field->alias,array( self::BELONGS_TO,'Manufacturer', $field->alias, 'select'=> "`{$field->alias}'_manufacturer`.`name`" ));
                    break;
				}
			}
		}
	}

    public function getTableFields(){
        $fields = array();
        if ( $this->_product ){
            foreach( $this->_product->fields as $field ){
    			if( $field->is_column_table ){
					$f['name'] = $field->alias;

					switch( $field->field_type ){
						case TypeField::STRING:						
						    $f['value'] = '$data->'.$field->alias;
						break;
    					case TypeField::LISTS:
                            
							if ($field->is_multiple_select){
								$f['value'] = '$data->getRecordItems('.$field->alias.')';
							} else
								$f['value'] = 'isset($data->'.$field->alias.'Item) ? $data->'.$field->alias.'Item->name : null';
                            
                            if ( $field->is_filter ) {
    							$f['filter'] = CHtml::listData( $this->getListFilter($field->list_id) , 'id', 'name');
							}
                            
						break;
                        case TypeField::BOOLEAN:
							if ( $field->is_filter ) {
    							$f['filter'] = CHtml::activeDropDownList(   $this,
                                                                                $field->alias,
                                                                                array(1=>"Yes",0=>"No"),
                                                                                array("empty"=>"")
                                                                                );
							}
						break;
                        

    					case TypeField::CATEGORIES:
                            if ($field->is_multiple_select)
                                $f['value'] = '$data->getRecordCategory("'.$field->alias.'")';
                            else
								$f['value'] = 'isset($data->'.$field->alias.'Category) ? $data->'.$field->alias.'Category->name : null';

							if ( $field->is_filter ) {
								$f['filter'] = CHtml::listData($this->getCategoryFilter($field) , 'id', 'name');
							}
						break;                        
    					case TypeField::MANUFACTURER:
							if ( $field->is_multiple_select )
								$f['value'] = '$data->getRecordManufacturer("'.$field->alias.'")';
							else
								$f['value'] = 'isset($data->'.$field->alias.'Manufacturer) ? $data->'.$field->alias.'Manufacturer->name : null';

							if ( $field->is_filter ) {
                                    $listData = CHtml::listData($this->getManufacturerFilter($field) , 'id', 'name') ;
                                    $htmlOptions = $field->is_multiple_select ? array("multiple"=>true,"class"=>"chzn-select") : null;
                                    $htmlOptions['empty'] = "";
									$f['filter'] = CHtml::activeDropDownList(   $this,
                                                                                $field->alias,
                                                                                $listData,
                                                                                $htmlOptions
                                                                                );


							}
						break;                        
					}

					if ( $field->is_filter == 0 && !isset($f['filter']) ) $f['filter'] = false;

						$fields[] = $f;
						unset($f);
    			}
            }            
        }
        return $fields;
    }

    public function getAdminTableFields($update = false)
    {
        $tableFields = array();
        
		if ( $update === false ){
            //$this->setProductFieldsOrder("t.position");

            if ( $this->_product ){
                foreach( $this->_product->fields as $field ){

					if( $field->is_column_table_admin ){
						$f['name'] = $field->alias;

                        if ( $field->is_editing_table_admin ) {
                            $name = $this->productName.'[$data->id]['.$field->alias.']';

                            $f['type']='raw';
                            $f['value'] = 'CHtml::textField("'.$name.'",$data->'.$field->alias.',array("class"=>"filter_price"));';
                        }

						switch( $field->field_type ){
							case TypeField::PRICE:
								if ( !$field->is_editing_table_admin ) {
                                    $f['type']='text';
                                    $f['value'] = '$data->'.$field->alias;
                                }

							break;
    						case TypeField::STRING:

    							if ( !$field->is_editing_table_admin ) {
                                    $f['type']='text';
                                    $f['value'] = '$data->'.$field->alias;
                                }

							break;
    						case TypeField::BOOLEAN:

    							if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';
                                    $htmlOptions = '';
                                    if ( !$field->is_mandatory ) $htmlOptions = 'array("empty"=>"")';
                                    $f['value'] = 'CHtml::dropDownList("'.$name.'",$data->'.$field->alias.',array(1=>"Yes",0=>"No"),'.$htmlOptions.');';
                                }


								if ( $field->is_filter ) {
    								$f['filter'] = CHtml::dropDownList($field->alias,null,array(1=>"Yes",0=>"No"),array("empty"=>""));
								}
							break;
							case TypeField::LISTS:
                                
                                if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';
                                    
                                    $multiple = 'array()';
                                    if ($field->is_multiple_select){
                                        $name = $name.'[]';
                                        $multiple = 'array("multiple"=>true,"class"=>"chzn-select")';
                                    }
    
                                    $f['value'] = 'CHtml::dropDownList("'.$name.'", $data->'.$field->alias.',
                                                                            CHtml::listData($data->getListFilter('.$field->list_id.') , "id", "name"),
                                                                            '.$multiple.'
                                                                            );';
                                    
                                                                        
                                } else {
                                    
        							if ($field->listField->is_multiple_select)
    									$f['value'] = '$data->getRecordItems("'.$field->alias.'")';
    								else
    									$f['value'] = 'isset($data->'.$field->alias.') ? $data->'.$field->alias.'->name : null';                                    
                                    
                                }
                                
								if ( $field->is_filter ) {
									$f['filter'] = CHtml::listData( $this->getListFilter($field->list_id) , 'id', 'name');
								}

							break;

							case TypeField::CATEGORIES:

                                if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';

                                    $multiple = 'array()';
                                    if ($field->is_multiple_select){
                                        $name = $name.'[]';
                                        $multiple = 'array("multiple"=>true,"class"=>"chzn-select")';
                                    }

                                    $f['value'] = 'CHtml::dropDownList("'.$name.'",
                                                                        $data->'.$field->alias.',
                                                                        CHtml::listData($data->getCategoryFilter('.$field->category_id.') , "id", "name"),
                                                                        '.$multiple.'
                                                                        );';

                                } else {
                                    if ($field->is_multiple_select)
                                        $f['value'] = '$data->getRecordCategory("'.$field->alias.'")';
                                    else
        							    $f['value'] = 'isset($data->'.$field->alias.'Category) ? $data->'.$field->alias.'Category->name : null';
                                }

								if ( $field->is_filter ) {
									$f['filter'] = CHtml::listData($this->getCategoryFilter($field->category_id) , 'id', 'name');
								}

							break;

							case TypeField::MANUFACTURER:

                                if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';

                                    $multiple = 'array()';
                                    if ($field->is_multiple_select){
                                        $name = $name.'[]';
                                        $multiple = 'array("multiple"=>true,"class"=>"chzn-select","data-placeholder"=>"")';
                                    }
        						    $f['value'] = 'CHtml::dropDownList("'.$name.'",
                                                                        $data->'.$field->alias.',
                                                                        CHtml::listData($data->getManufacturerFilter('.$field->manufacturer_id.') , "id", "name"),
                                                                        '.$multiple.'
                                                                        );';

                                } else {
        							if ( $field->is_multiple_select )
    									$f['value'] = '$data->getRecordManufacturer("'.$field->alias.'")';
    								else
    									$f['value'] = 'isset($data->'.$field->alias.'Manufacturer) ? $data->'.$field->alias.'Manufacturer->name : null';
                                }

								if ( $field->is_filter ) {
                                    $listData = CHtml::listData($this->getManufacturerFilter($field) , 'id', 'name') ;
                                    $htmlOptions = $field->is_multiple_select ? array("multiple"=>true,"class"=>"chzn-select","data-placeholder"=>" ") : null;
                                    $htmlOptions['empty'] = "";
									$f['filter'] = CHtml::activeDropDownList($this,$field->alias,$listData,$htmlOptions);
								}

							break;

							case TypeField::DATETIME:

							break;
						}

						if ( $field->is_filter == 0 && !isset($f['filter']) ) $f['filter'] = false;


						$tableFields[] = $f;
						unset($f);
					}
				}
			}
		}

		return $tableFields;
	}

    public function getListFilter($list_id)
	{
        $name = "listFilterСache_".$list_id;

        if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

		if( is_numeric($list_id) ){
			Yii::app()->params[$name] = ListItem::model()->findAll('list_id = :list_id', array(":list_id"=>$list_id) );
		}

		return Yii::app()->params[$name];
	}

    public function getRecordItems($name, $sSep = ', ')
	{
        if ( empty($this->{$name}) ) return;
        
        $aRes = array();
        foreach ($this->{$name} as $item) {
            $aRes[] = $item->name;
        }

        return implode($sSep, $aRes);
    }

    public function getRecordManufacturer($name, $sSep = ', ')
    {
       $aRes = array();

       foreach ($this->{$name} as $item) {
          $aRes[] = $item->name;
       }

       return implode($sSep, $aRes);
    }

    public function getManufacturerFilter($manufacturer_id)
	{
        $name = "manufacturerFilterСache";

        //if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

		if ( $this->_manufacturerFilter === null ){
			if( is_numeric($manufacturer_id) ){
				$this->_manufacturerFilter = Manufacturer::model()->findByPk($manufacturer_id)->descendants()->findAll();
			} else {
				$this->_manufacturerFilter = Manufacturer::model()->findAll();
			}
            Yii::app()->params[$name] = $this->_manufacturerFilter ;
		}
		return $this->_manufacturerFilter;
	}

    public function getRecordCategory($name, $sSep = ', ')
    {
        $aRes = array();

        foreach ($this->{$name} as $item) {
            $aRes[] = $item->name;
        }

       return implode($sSep, $aRes);
    }

    public function getCategoryFilter($category_id)
	{
        $name = "categoryFilterСache";

        //if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

		if ( $this->_categoryFilter === null ){
			if( is_numeric($category_id) ){
				$this->_categoryFilter = Category::model()->findByPk($category_id)->descendants()->findAll();
			} else {
				$this->_categoryFilter = Category::model()->findAll();
			}

            Yii::app()->params[$name] = $this->_categoryFilter ;
		}
		return $this->_categoryFilter;
	}

    public function search()
	{
		$criteria = new CDbCriteria;		
        $criteria->with = array_keys($this->getMetaData()->relations);
        
		return new CActiveDataProvider($this,array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>'20',
                'pageVar'=>'page'
            )
        ));
	}
    
    public function getMotelCForm()
	{
		$form = array(
			'attributes' => array(
                'id' => "recordForm",
                'class' => 'well',
				'enctype' => 'multipart/form-data',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),
			'elements' => $this->getTabsFormElements(false),
			'buttons' => array(
				'<br/>',
				'submit'=>array(
					'type' => 'submit',
					'label' => $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' => "btn"
				),
			),
		);

		return new CForm($form,$this);
	}    

    public function getFormField($field){
        $return = array($field->alias => TypeField::getFieldFormData($field->field_type) );

        switch( $field->field_type ){
            case TypeField::IMAGE :
				$return[$field->alias] = $field->getElementCForm();
			break;

            case TypeField::DATETIME :
				$return[$field->alias] = $field->getElementCForm();
			break;

            case TypeField::TEXT :
				$return[$field->alias]['rows'] = $field->rows;
			break;

            case TypeField::LISTS :
				$return[$field->alias]['items'] = CHtml::listData(ListItem::model()->findAll('list_id = :list_id',array(':list_id'=>$field->list_id)), 'id', 'name');
    			if ( $field->is_multiple_select ){

					$selected = array();
					if ( $this->{$field->alias} ) {
						foreach( $this->{$field->alias."Items"} as $Item ){
							$selected[] = $Item->id;
						}
					}

                    $this->{$field->alias} = $selected;

					$return[$field->alias]['multiple'] = true;
					$return[$field->alias]['class'] = 'chzn-select';
				}
			break;

    		case TypeField::CATEGORIES :

				if( $field->category_id ){
					$category = Category::model()->findByPk($field->category_id)->descendants()->findAll();
				} else {
					$category = Category::model()->findAll();
				}

				$return[$field->alias]['items'] = CHtml::listData($category, 'id', 'name');
                if ( $field->is_multiple_select ){
    				$selected = array();
					if ( $this->{$field->alias} ) {
						foreach( $this->{$field->alias} as $Item ){
							$selected[] = $Item->id;
						}
					}

                    $this->{$field->alias} = $selected;

					$return[$field->alias]['multiple'] = true;
					$return[$field->alias]['class'] = 'chzn-select';
                }
			break;

		    case TypeField::MANUFACTURER :

				if( $field->manufacturer_id ){
					$manufacturer = Manufacturer::model()->findByPk($field->manufacturer_id)->descendants()->findAll();
                } else {
					$manufacturer = Manufacturer::model()->findAll();
				}

                $return[$field->alias]['items'] = CHtml::listData($manufacturer, 'id', 'name');
                if ( $field->is_multiple_select ){
    				$selected = array();
					if ( $this->{$field->alias} ) {
					    foreach( $this->{$field->alias} as $item ){
							$selected[] = $item->id;
						}
					}

                    $this->{$field->alias} = $selected;

					$return[$field->alias]['multiple'] = true;
					$return[$field->alias]['class'] = 'chzn-select';
                }
			break;
		}

        return $return;
    }

    protected function searchForId($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['id'] === $id) {
               return $key;
           }
       }
       return null;
    }

    public function getTabsFormElements($isEdit = true){

    	$arTabs = array(array("id"=>0,"position"=>0,"name"=>"Общее","content"=>array(),'htmlOptions'=>array('class'=>'active')));
        $tabs = $this->getProductTab();
        if ( !empty($tabs) ){
            foreach($tabs as $tab){
                $arTabs[] = array("id"=>$tab->id,"position"=>$tab->position,"name"=>$tab->name,"content"=>array(),'productId'=> $isEdit ? $this->getProductID() : null );
            }
        }
        $this->setProductFieldsOrder("fieldTab.position");
        if ( $this->_product ){
            foreach( $this->_product->fields as $field ){
                
                $id = $this->searchForId( $field->tab_id > 0 ? $field->tab_id : 0 , $arTabs);

                if( isset( $arTabs[$id] ) ){
                    $field = $this->getFormField($field);
                    $arTabs[$id]['content'][key($field)] = $field[key($field)];
                }
            }
        }

        return Tab::Tabs($arTabs);
    }
    public function getProductTab(){
        return Tab::model()->findAll(array(
            'order'=>'t.position',
            'condition'=>'product_id = :product_id',
            'params'=>array(":product_id"=> $this->getProductID())
        ));
    }
    
    public function setProductFieldsOrder($order){
    	$this->_productFieldsOrder = $order;
	}

    public function getProductFieldsOrder(){
    	return $this->_productFieldsOrder;
	}  
    
    public function afterSave()
	{
        $productFields = $this->_product->fields;
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
					case TypeField::LISTS :
                        if ($field->is_multiple_select){

							RecordList::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->id));

							if ( isset($this->{$field->alias}) && !empty($this->{$field->alias}) ){
								foreach ($this->{$field->alias} as $list_item_id) {
									$RecordList = new RecordList();
									$RecordList->product_id = $this->getProductID();
									$RecordList->record_id = $this->id;
									$RecordList->list_item_id = $list_item_id;
									if ( !$RecordList->save() ) throw new CException("ERROR SAVE LISTS");
								}
							}
						}
                    break;

					case TypeField::CATEGORIES :
						if ($field->is_multiple_select){
							RecordCategory::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->id));

							if ( isset($this->{$field->alias}) && !empty($this->{$field->alias}) ){
								foreach ($this->{$field->alias} as $category_id) {
									$RecordCategory = new RecordCategory();
									$RecordCategory->product_id = $this->getProductID();
									$RecordCategory->record_id = $this->id;
									$RecordCategory->category_id = $category_id;
									if ( !$RecordCategory->save() ) {
                                        throw new CException("ERROR SEVE CATEGORIES ".$RecordCategory->category_id );
									}
								}
							}
						}
                    break;

					case TypeField::MANUFACTURER :
						if ($field->is_multiple_select){
							RecordManufacturer::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->id));

							if ( isset($this->{$field->alias}) && !empty($this->{$field->alias}) ){
								foreach ($this->{$field->alias} as $manufacturer_id) {
									$RecordManufacturer = new RecordManufacturer();
									$RecordManufacturer->product_id = $this->getProductID();
									$RecordManufacturer->record_id = $this->id;
									$RecordManufacturer->manufacturer_id = $manufacturer_id;
									if ( !$RecordManufacturer->save() ) throw new CException("ERROR SEVE MANUFACTURES");
								}
							}
						}
                    break;

    				case TypeField::DATETIME :

						if ($field->is_multiple_select){
							RecordDateTime::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->id));

							if ( isset($this->{$field->alias}) && !empty($this->{$field->alias}) ){
								foreach ($this->{$field->alias} as $datetime) {
									$RecordDateTime = new RecordDateTime();
									$RecordDateTime->product_id = $this->getProductID();
									$RecordDateTime->record_id = $this->id;
									$RecordDateTime->datetime = $datetime;
									if ( !$RecordDateTime->save() ) throw new CException("ERROR SAVE DATETIME");
								}
							}
						}
                    break;

        			case TypeField::IMAGE :

						// новые файлы
						if ( is_array($this->{$field->alias}) && !empty($this->{$field->alias}) ){
                            $folder = $this->getRecordFolder();
                            if(!is_dir($folder)) mkdir($folder,0777,true);
    					    foreach($this->{$field->alias} as $file){
			                    $file->saveAs($folder.$file->getName());
    					    }
						}


						// удлаение старых файлов
                        $name = md5($field->alias);
						if ( isset($_POST[get_class($this)][$name]) ){
							${$name} = $_POST[get_class($this)][$name];
							if ( is_array( ${$name} ) && !empty( ${$name} ) ){
								$folder = $this->getRecordFolder();
								foreach( ${$name} as $file){
									$this->getRecordDeleteFile($folder, $file );
								}
							}
						}

                    break;
				}
			}
		}

	}    
    
}