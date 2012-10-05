<?php

class Record extends CActiveRecord
{

    private $_attributes=array();
	private $_productId = null;
	private $_product = null;
	private $_with = null;
	private $_productFields = null;
    private $_tableFields = null;
    private $_searchFields = null;

	private $_manufacturerFilter = null;
	private $_categoryFilter = null;
    private $_productFieldsOrder = null;

    public $productName = null;
    public $data = null;

    public static function model($className=__CLASS__)
	{
    	eval("class ".$className." extends Record{}");
		return parent::model($className);
	}

    public function __construct($scenario = 'add')
	{
		parent::__construct($scenario);

        $this->productName = get_class($this);
        $this->setProduct();

        $this->getProductFields();

        Yii::setPathOfAlias('files', Yii::getPathOfAlias('webroot')."/data/".$this->productName."/");
    	Yii::setPathOfAlias('url', Yii::app()->baseUrl."/data/".$this->productName."/");
	}


	public function __get($name)
	{
		try {
			return parent::__get($name);
		} catch (Exception $exc) {
            $name = strtolower($name);
            if( isset($this->_attributes[$name]) )
                return $this->_attributes[strtolower($name)];
            else
                throw new CException("NOT ".$name );
		}
	}

	public function __set($name,$value)
	{
		try {
			parent::__set($name,$value);
		} catch (Exception $exc) {
			return $this->_attributes[strtolower($name)] = $value;
		}
	}

	public function getProductID()
	{
		return $this->_product->id;
	}

    private function setProduct()
    {
        $name = $this->productName."ProductСache";

        if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

        if ( $this->_product === null ) {
            $this->_product = Product::model()->with('productFields')->find(array('condition'=>'t.alias = :alias','params'=>array(':alias'=> $this->productName )));
            Yii::app()->params[$name] = $this->_product;
        }
    }

	public function getProductFields($update = false)
	{

		if ( isset(Yii::app()->params[$this->tableName()]) && $update === false ) return Yii::app()->params[$this->tableName()];

		if ( $this->_productFields === null || $update === true ) {

			if ( $this->_product ){
				$this->_productFields = $this->_product->productFields();

				if ( $this->_productFields ){
					foreach ($this->_productFields as $field) {
						if ( $field->is_column_table )
							switch( $field->field_type ){
								case TypeField::STRING:
									$this->_with['stringField'] = 'stringField';
								break;
								case TypeField::TEXT:
									$this->_with['textField'] = 'textField';
								break;
								case TypeField::INTEGER:
									$this->_with['integerField'] = 'integerField';
								break;
    							case TypeField::DOUBLE:
									$this->_with['doubleField'] = 'doubleField';
								break;
								case TypeField::PRICE:
									$this->_with['priceField'] = 'priceField';
								break;
								case TypeField::LISTS:
									$this->_with['listField'] = 'listField';
								break;
								case TypeField::CATEGORIES:
									$this->_with['categoryField'] = 'categoryField';
								break;
								case TypeField::MANUFACTURER:
									$this->_with['manufacturerField'] = 'manufacturerField';
								break;
								case TypeField::IMAGE:
									$this->_with['imageField'] = 'imageField';
								break;
								case TypeField::DATETIME:
									$this->_with['dateTimeField'] = 'dateTimeField';
								break;
							}
					}
                    $this->_with['fieldTab'] = 'fieldTab';

                    $criteria = new CDbCriteria;
                    $criteria->condition = 'product_id=:product_id';
                    $criteria->with = $this->_with;
                    $criteria->params = array(':product_id'=>$this->getProductID());
                    if ( $this->getProductFieldsOrder() ) $criteria->order = $this->getProductFieldsOrder();

					$this->_productFields = ProductField::model()->findAll($criteria);
				}

			} else
				throw new CException("NOT product");

		}

		Yii::app()->params[$this->tableName()] = $this->_productFields;

		return $this->_productFields;
	}

	public function getRecordLinkAlias($text,$alias){
		return CHtml::link($text, Yii::app()->createUrl('product/view',array('product'=> get_class($this), "alias"=>$alias) ) );
	}

	public function getRecordLinkId($text,$id){
		return CHtml::link($text, Yii::app()->createUrl('product/view',array('product'=> get_class($this), "id"=>$id) ) );
	}

    public function setProductFieldsOrder($order){
		$this->_productFieldsOrder = $order;
	}

    public function getProductFieldsOrder(){
    	return $this->_productFieldsOrder;
	}

	public function getTableFields($update = false)
	{

		if ( $this->_tableFields === null && $update === false ){
            $this->setProductFieldsOrder("t.position");
			$productFields = $this->getProductFields(true);

			if ( $productFields ){
				foreach( $productFields as $field ){
					if( $field->is_column_table ){
						$f['name'] = $field->alias;

						switch( $field->field_type ){
							case TypeField::STRING:
								$f['type']='raw';
								//$f['value'] = '$data->alias ? $data->getRecordLinkAlias($data->'.$field->alias.',$data->alias) : $data->getRecordLinkId($data->'.$field->alias.',$data->id)';
                                $f['value'] = '$data->'.$field->alias;
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
							case TypeField::LISTS:
								if ($field->listField->is_multiple_select)
									$f['value'] = '$data->getRecordItems("'.$field->alias.'Items")';
								else
									$f['value'] = 'isset($data->'.$field->alias.'Item) ? $data->'.$field->alias.'Item->name : null';
							break;
							case TypeField::CATEGORIES:
                                if ($field->categoryField->is_multiple_select)
                                    $f['value'] = '$data->getRecordCategory("'.$field->alias.'")';
                                else
									$f['value'] = 'isset($data->'.$field->alias.'Category) ? $data->'.$field->alias.'Category->name : null';

								if ( $field->is_filter ) {
									$f['filter'] = CHtml::listData($this->getCategoryFilter($field) , 'id', 'name');
								}
							break;
							case TypeField::MANUFACTURER:
								if ( $field->manufacturerField->is_multiple_select )
									$f['value'] = '$data->getRecordManufacturer("'.$field->alias.'")';
								else
									$f['value'] = 'isset($data->'.$field->alias.'Manufacturer) ? $data->'.$field->alias.'Manufacturer->name : null';

								if ( $field->is_filter ) {
                                    $listData = CHtml::listData($this->getManufacturerFilter($field) , 'id', 'name') ;
                                    $htmlOptions = $field->manufacturerField->is_multiple_select ? array("multiple"=>true,"class"=>"chzn-select") : null;
                                    $htmlOptions['empty'] = "";
									$f['filter'] = CHtml::activeDropDownList(   $this,
                                                                                $field->alias,
                                                                                $listData,
                                                                                $htmlOptions
                                                                                );


								}
							break;
							case TypeField::DATETIME:
								if ( $field->dateTimeField->is_multiple_select )
									$f['value'] = '$data->getRecordDateTime("'.$field->alias.'");';
									//$f['value'] = 'isset($data->'.$field->alias.'->datetime) ? $data->'.$field->alias.'->datetime : null ;';
								else {

									switch ($field->dateTimeField->type) {
										case DateTimeField::DATETIME:
											$f['value'] = 'Yii::app()->dateFormatter->formatDateTime($data->'.$field->alias.',"medium","short");';
										break;
										case DateTimeField::DATE:
											$f['value'] = 'Yii::app()->dateFormatter->formatDateTime($data->'.$field->alias.',"medium",null);';
										break;
										case DateTimeField::TIME:
											$f['value'] = 'Yii::app()->dateFormatter->formatDateTime($data->'.$field->alias.',null,"short");';
										break;

									}
								}


								if ( $field->is_filter && !$field->dateTimeField->is_multiple_select) {
									$f['filter'] = Yii::app()->controller->widget(	'zii.widgets.jui.CJuiDatePicker', array(
																					'model'=>$this,'attribute'=>$field->alias,
																					'language'=>Yii::app()->getLanguage(),
																					'htmlOptions'=>array('onclick'=>'$(this).datepicker( $.datepicker.regional["'.Yii::app()->getLanguage().'"]);$(this).datepicker().focus();')),true);
								}
							break;
						}

						if ( $field->is_filter == 0 && !isset($f['filter']) ) $f['filter'] = false;

						$this->_tableFields[] = $f;
						unset($f);
					}
				}
			}
		}

		return $this->_tableFields;
	}

    public function getAdminTableFields($update = false)
	{

		if ( $this->_tableFields === null && $update === false ){
            $this->setProductFieldsOrder("t.position");
			$productFields = $this->getProductFields(true);

			if ( $productFields ){
				foreach( $productFields as $field ){

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
                                    $f['value'] = 'CHtml::dropDownList("'.$name.'",$data->'.$field->alias.',array(1=>"Yes",0=>"No"),array("empty"=>""));';
                                }


								if ( $field->is_filter ) {
    								$f['filter'] = CHtml::dropDownList($field->alias,null,array(1=>"Yes",0=>"No"),array("empty"=>""));
								}
							break;
							case TypeField::LISTS:
								if ($field->listField->is_multiple_select)
									$f['value'] = '$data->getRecordItems("'.$field->alias.'Items")';
								else
									$f['value'] = 'isset($data->'.$field->alias.'Item) ? $data->'.$field->alias.'Item->name : null';
							break;

							case TypeField::CATEGORIES:

                                if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';

                                    $multiple = 'array()';
                                    if ($field->categoryField->is_multiple_select){
                                        $name = $name.'[]';
                                        $multiple = 'array("multiple"=>true,"class"=>"chzn-select")';
                                    }

                                    $f['value'] = 'CHtml::dropDownList("'.$name.'",
                                                                        $data->'.$field->alias.',
                                                                        CHtml::listData($data->getCategoryFilter('.$field->categoryField->category_id.') , "id", "name"),
                                                                        '.$multiple.'
                                                                        );';

                                } else {
                                    if ($field->categoryField->is_multiple_select)
                                        $f['value'] = '$data->getRecordCategory("'.$field->alias.'")';
                                    else
        							    $f['value'] = 'isset($data->'.$field->alias.'Category) ? $data->'.$field->alias.'Category->name : null';
                                }

								if ( $field->is_filter ) {
									$f['filter'] = CHtml::listData($this->getCategoryFilter($field->categoryField->category_id) , 'id', 'name');
								}

							break;

							case TypeField::MANUFACTURER:

                                if ( $field->is_editing_table_admin ) {
                                    $f['type']='raw';

                                    $multiple = 'array()';
                                    if ($field->manufacturerField->is_multiple_select){
                                        $name = $name.'[]';
                                        $multiple = 'array("multiple"=>true,"class"=>"chzn-select","data-placeholder"=>"")';
                                    }
        						    $f['value'] = 'CHtml::dropDownList("'.$name.'",
                                                                        $data->'.$field->alias.',
                                                                        CHtml::listData($data->getManufacturerFilter('.$field->manufacturerField->manufacturer_id.') , "id", "name"),
                                                                        '.$multiple.'
                                                                        );';

                                } else {
        							if ( $field->manufacturerField->is_multiple_select )
    									$f['value'] = '$data->getRecordManufacturer("'.$field->alias.'")';
    								else
    									$f['value'] = 'isset($data->'.$field->alias.'Manufacturer) ? $data->'.$field->alias.'Manufacturer->name : null';
                                }

								if ( $field->is_filter ) {
                                    $listData = CHtml::listData($this->getManufacturerFilter($field) , 'id', 'name') ;
                                    $htmlOptions = $field->manufacturerField->is_multiple_select ? array("multiple"=>true,"class"=>"chzn-select","data-placeholder"=>" ") : null;
                                    $htmlOptions['empty'] = "";
									$f['filter'] = CHtml::activeDropDownList($this,$field->alias,$listData,$htmlOptions);
								}

							break;

							case TypeField::DATETIME:

							break;
						}

						if ( $field->is_filter == 0 && !isset($f['filter']) ) $f['filter'] = false;


						$this->_tableFields[] = $f;
						unset($f);
					}
				}
			}
		}

		return $this->_tableFields;
	}

	public function getManufacturerFilter($manufacturer_id)
	{
        $name = "manufacturerFilterСache";

        if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

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

	public function getCategoryFilter($category_id)
	{
        $name = "categoryFilterСache";

        if ( isset(Yii::app()->params[$name]) ) return Yii::app()->params[$name];

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

	public function getRecordItems($name, $sSep = ', ')
	{
       $aRes = array();
       foreach ($this->{$name} as $item) {
          $aRes[] = $item->name;
       }

       return implode($sSep, $aRes);
    }

    public function getRecordCategory($name, $sSep = ', ')
	{
        $aRes = array();

        foreach ($this->{$name} as $item) {
            $aRes[] = $item->name;
        }

       return implode($sSep, $aRes);
    }

    public function getRecordDateTime($name, $sSep = ', ')
	{
       $aRes = array();

	   if ( !empty($this->{$name}) )
		foreach ($this->{$name} as $item) {
		   $aRes[] = $item->datetime;
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

	public function setGoodsAttributes()
	{
		$productFields = $this->getProductFields();

		if ( $productFields ){
			foreach( $productFields as $field ){
				$this->setAttribute($field->alias,$field->alias);
			}
		}
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
				$return[$field->alias] = $field->imageField->getElementCForm();
			break;

            case TypeField::DATETIME :
				$return[$field->alias] = $field->dateTimeField->getElementCForm();
			break;

            case TypeField::TEXT :
				$return[$field->alias]['rows'] = $field->textField->rows;
			break;

            case TypeField::LISTS :
				$return[$field->alias]['items'] = CHtml::listData(ListItem::model()->findAll('list_id = :list_id',array(':list_id'=>$field->listField->list_id)), 'id', 'name');
    			if ( $field->listField->is_multiple_select ){

					$selected = array();
					if ( $this->{$field->alias."Items"} ) {
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

				if( $field->categoryField->category_id ){
					$category = Category::model()->findByPk($field->categoryField->category_id)->descendants()->findAll();
				} else {
					$category = Category::model()->findAll();
				}

				$return[$field->alias]['items'] = CHtml::listData($category, 'id', 'name');
                if ( $field->categoryField->is_multiple_select ){
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

				if( $field->manufacturerField->manufacturer_id ){
					$manufacturer = Manufacturer::model()->findByPk($field->manufacturerField->manufacturer_id)->descendants()->findAll();
                } else {
					$manufacturer = Manufacturer::model()->findAll();
				}

                $return[$field->alias]['items'] = CHtml::listData($manufacturer, 'id', 'name');
                if ( $field->manufacturerField->is_multiple_select ){
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
        $fields = $this->getProductFields(true);
        if ( $fields ){
            foreach($fields as $field){

                $id = $this->searchForId( $field->fieldTab->tab_id > 0 ? $field->fieldTab->tab_id : 0 , $arTabs);

                if( isset( $arTabs[$id] ) ){
                    $field = $this->getFormField($field);
                    $arTabs[$id]['content'][key($field)] = $field[key($field)];
                }
            }
        }

        return Tab::Tabs($arTabs);
    }


    public function getRelationsNameArray()
	{
        return array_keys($this->relations());
    }

	public function relations()
	{
        $relations = array();

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
					case TypeField::LISTS :

                        if ($field->listField->is_multiple_select) {
                            $name = $field->alias.'Items';
    						$relations[$name] = array(	self::MANY_MANY,
														'ListItem', 'record_list(record_id, list_item_id)',
														'condition'=> '`'.$name."_".$name.'`.`product_id` = :product_id',
														'params' => array(":product_id" => $this->getProductID() ),
														'together'=>true
													);
						} else
                            $relations[$field->alias.'Item'] = array( self::BELONGS_TO,'ListItem', $field->alias );
                    break;
					case TypeField::CATEGORIES :
                        if ($field->categoryField->is_multiple_select)
    						$relations[$field->alias] = array(	self::MANY_MANY,
																'Category', 'record_category(record_id, category_id)',
																'condition'=> $field->alias.'_category.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together'=>true
															);
                        else
                            $relations[$field->alias.'Category'] = array( self::BELONGS_TO,'Category', $field->alias, 'select'=> "`{$field->alias}'_category`.`name`" );
                    break;

					case TypeField::MANUFACTURER :
                        if ($field->manufacturerField->is_multiple_select)
    						$relations[$field->alias] = array(	self::MANY_MANY,
																'Manufacturer', 'record_manufacturer(record_id, manufacturer_id)',
                                                                'select'=> "`{$field->alias}'_manufacturer`.`name`",
																'condition'=> $field->alias.'_manufacturer.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together'=>true
															);
                        else
                            $relations[$field->alias.'Manufacturer'] = array( self::BELONGS_TO,'Manufacturer', $field->alias, 'select'=> "`{$field->alias}'_manufacturer`.`name`" );
                    break;

    				case TypeField::DATETIME :
                        if ($field->dateTimeField->is_multiple_select)
    						$relations[$field->alias] = array( self::HAS_MANY,'RecordDateTime','record_id');

                    break;

				}
			}
		}

		return $relations;
	}

	public function beforeSave1()
	{
		parent::beforeSave();

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
		        	case TypeField::DATETIME :

						if ($field->dateTimeField->is_multiple_select){
							$dates = explode(',', $this->{$field->alias});
							$new = array();
							if (!empty($dates)){
								foreach ($dates as $date) {
									$new[] = $field->dateTimeField->formatedDateTimeSave($date);
								}
							}
							$this->{$field->alias} = $new;
						} else {
							$this->{$field->alias} = $field->dateTimeField->formatedDateTimeSave($this->{$field->alias});
						}
					break;
				}
			}
		}

		return true;
	}

	public function afterFind()
	{
		parent::afterFind();

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
		        	case TypeField::DATETIME:
                        if ( !empty($this->{$field->alias}) ){
    						if ( $field->dateTimeField->is_multiple_select ) {

								if ( !empty($this->{$field->alias}) )
									foreach ($this->{$field->alias} as $datetime) {
										$datetime->datetime = $field->dateTimeField->formatedDateTime($datetime->datetime);
									}

    						} else {
                                $this->{$field->alias} = $field->dateTimeField->formatedDateTime($this->{$field->alias});
    						}
				        }

					break;

		        	case TypeField::IMAGE:
                        // получаем имеющиеся файлы если они есть
                        if ( is_dir($this->getRecordFolder()) ) $this->{md5($field->alias)} = LoaderFiles::Load($this->getRecordFolder());
					break;
				}
			}
		}

		return true;
	}

	protected function getRecordFolder()
	{
		return Yii::getPathOfAlias('files').DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR;
	}

    protected function getRecordDeleteFile($path,$filename)
	{
        $file = $path.basename($filename);
        if ( is_file($file) && is_writable($file) ){
            return unlink($file) ? true : false;
        } else
		    return false;
	}

	public function afterSave()
	{
        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
					case TypeField::LISTS :
                        if ($field->listField->is_multiple_select){

							RecordList::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->id));

							if ( isset($this->{$field->alias}) && !empty($this->{$field->alias}) ){
								foreach ($this->{$field->alias} as $list_item_id) {
									$RecordList = new RecordList();
									$RecordList->product_id = $this->getProductID();
									$RecordList->record_id = $this->id;
									$RecordList->list_item_id = $list_item_id;
									if ( !$RecordList->save() ) throw new CException("ERROR SEVE LISTS");
								}
							}
						}
                    break;

					case TypeField::CATEGORIES :
						if ($field->categoryField->is_multiple_select){
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
						if ($field->manufacturerField->is_multiple_select){
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

						if ($field->dateTimeField->is_multiple_select){
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

	public function rules()
	{
		$rules      = array();
		$required   = array();
		$numerical  = array();
		$safe       = array('title','keywords','description');
		$unique     = array("alias");

		$productFields = $this->getProductFields();

		if ( $productFields ){
			foreach( $productFields as $field ){
				if ( $field->is_mandatory ) $required[] = $field->alias;

				switch( $field->field_type ){
					case TypeField::TEXT :
						$safe[] = $field->alias;
						$rules[] = array($field->alias,'length','min'=> $field->textField->min_length,'max'=>$field->textField->max_length,'allowEmpty'=>true );
					break;
					case TypeField::STRING :
						$safe[] = $field->alias;
						$rules[] = array($field->alias,'length','min'=> $field->stringField->min_length,'max'=>$field->stringField->max_length,'allowEmpty'=>true );
					break;
					case TypeField::INTEGER :
						$rules[] = array($field->alias, 'numerical', 'integerOnly'=>true,'min'=> $field->integerField->min_value ,'max'=>$field->integerField->max_value ,'allowEmpty'=>true);
					break;
    				case TypeField::DOUBLE :
						$rules[] = array($field->alias, 'numerical', );

                        if ( $field->doubleField->decimal ){
            				$rules[] = array($field->alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,'.$field->doubleField->decimal.'}?\s*$/',
    											'message' => Yii::t("fields",'Price has the wrong format (eg 10.50).')
    										);
                        }

					break;
					case TypeField::PRICE:

						$rules[] = array($field->alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,2}?\s*$/',
											'message' => Yii::t("products",'Price has the wrong format (eg 10.50).')
										);
						$price = array($field->alias, 'numerical', 'allowEmpty'=>$field->is_mandatory);

						if ( $field->priceField->max_value ) $price['max'] = $field->priceField->max_value;
						$rules[] = $price;

					break;
    				case TypeField::LISTS :
						if ($field->listField->is_multiple_select)
							$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true, 'allowEmpty'=>false
											));
						else
							$rules[] = array($field->alias, 'numerical', 'integerOnly'=>true,'allowEmpty'=>true);
					break;
    				case TypeField::CATEGORIES :
						if ($field->categoryField->is_multiple_select)
							$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true));
						else
							$rules[] = array($field->alias, 'numerical','integerOnly'=>true );

					break;
    				case TypeField::IMAGE :
						$rules[] = array($field->alias, 'ArrayValidator', 'validator'=>'file', 'params'=>array(
											'types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>false
										));

					break;
    				case TypeField::BOOLEAN :
						$rules[] = array($field->alias, 'boolean', 'allowEmpty'=> $field->is_mandatory );
					break;
        			case TypeField::DATETIME :
						if ( $field->dateTimeField->is_multiple_select )
							$rules[] = array($field->alias, 'type', 'type' => 'string');
						else
							$rules[] = array($field->alias, 'date', 'format'=> DateTimeField::getFormatLocale($field->dateTimeField->type),'allowEmpty'=> $field->is_mandatory );
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

		$productFields = $this->getProductFields();

		if ( $productFields ){
			foreach( $productFields as $field ){
				$labels[$field->alias] = $field->name;
			}
		}

		return $labels;
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = $this->getRelationsNameArray();

		foreach ($this->getProductFields() as $field) {
			if ( $field->is_filter ){
				switch( $field->field_type ){
    				case TypeField::STRING:
                        $name = $this->getTableAlias().'.'.$field->alias;
						$criteria->compare($name, $this->{$field->alias},true);
					break;
					case TypeField::INTEGER:
						$criteria->compare($field->alias, $this->{$field->alias});
					break;
    				case TypeField::BOOLEAN:
						$criteria->compare($field->alias, $this->{$field->alias});
					break;
					case TypeField::PRICE:
						$criteria->compare($field->alias, $this->{$field->alias});
					break;
					case TypeField::MANUFACTURER:
						if( $field->manufacturerField->is_multiple_select ){
							$criteria->compare($field->alias.'.id', $this->{$field->alias});
						} else {
							$criteria->compare($field->alias, $this->{$field->alias});
						}
					break;
					case TypeField::CATEGORIES:
						if( $field->categoryField->is_multiple_select ){
							$criteria->compare($field->alias.'.id', $this->{$field->alias});
						} else {
							$criteria->compare($field->alias, $this->{$field->alias});
						}
					break;
					case TypeField::DATETIME :
						$date = new DateTime($this->{$field->alias});
						if( $field->dateTimeField->is_multiple_select ){
							$criteria->compare($field->alias.'.id', $this->{$field->alias});
						} else {
							$criteria->compare($field->alias,$date->format('Y-m-d 00:00:00'));
						}
					break;
				}
			}
		}

		return new CActiveDataProvider($this,array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>'20',
                'pageVar'=>'page'
            )
        ));
	}

	public function beforeValidate()
	{
		foreach ($this->getProductFields() as $field) {
			switch( $field->field_type ){
				case TypeField::IMAGE:
					$this->{$field->alias} = CUploadedFile::getInstances($this,$field->alias);
				break;
			}
		}
		return true;
	}

    public function getProductTab(){
        $this->getProductFields();

        return Tab::model()->findAll(array(
            'order'=>'t.position',
            'condition'=>'product_id = :product_id',
            'params'=>array(":product_id"=> $this->getProductID())
        ));
    }
}
