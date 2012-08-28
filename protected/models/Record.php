<?php

class Record extends CActiveRecord
{
	private $_productId = null;
	private $_product = null;
	private $_with = null;
	private $_productFields = null;
	private $_tableFields = null;
    private $_searchFields = null;
    public $data = null;

	private $_manufacturerFilter = null;


	public function setProductID($v)
	{
		$this->_productId = $v;
	}

	public function getProductID()
	{
		return $this->_product->id;
	}

	public function getProductFields($update = false)
	{
		if ( $this->_productFields === null && $this->_product === null && $update === false ) {

			$this->_product = Product::model()->with('productFields')->find(array(
				'condition'=>'t.alias = :alias',
				'order'=>'productFields.position',
				'params'=>array(':alias'=> get_class($this))
			));

			if ( $this->_product ){
				$this->_productFields = $this->_product->productFields();
				$this->setProductID($this->_product->id);

				if ( $this->_productFields ){
					foreach ($this->_productFields as $field) {
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

					$this->_productFields = ProductField::model()->with($this->_with)->findAll(array(
												'condition'=>'product_id=:product_id',
												'order'=>'position',
												'params'=>array(':product_id'=>$this->getProductID())
											));


				}

			} else
				throw new CException("id NOT product_id");


		}

		return $this->_productFields;
	}

	public function getRecordLinkAlias($text,$alias){
		return CHtml::link($text, Yii::app()->createUrl('product/view',array('product'=> get_class($this), "alias"=>$alias) ) );
	}

	public function getRecordLinkId($text,$id){
		return CHtml::link($text, Yii::app()->createUrl('product/view',array('product'=> get_class($this), "id"=>$id) ) );
	}

	public function getTableFields($update = false)
	{

		if ( $this->_tableFields === null && $update === false ){
			$productFields = $this->getProductFields();

			if ( $productFields ){
				foreach( $productFields as $field ){
					if( $field->is_column_table ){
						$f['name'] = $field->alias;

						switch( $field->field_type ){
							case TypeField::STRING:
								$f['type']='raw';
								$f['value'] = '$data->alias ? $data->getRecordLinkAlias($data->'.$field->alias.',$data->alias) : $data->getRecordLinkId($data->'.$field->alias.',$data->id)';

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
									$f['filter'] = CHtml::listData($this->getManufacturerFilter($field) , 'id', 'name');
								}
							break;
							case TypeField::DATETIME:
								if ( $field->dateTimeField->is_multiple_select )
									$f['value'] = '1';
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

	private function getManufacturerFilter($field)
	{
		if ( $this->_manufacturerFilter === null ){
			if( $field->manufacturerField->manufacturer_id ){
				$this->_manufacturerFilter = Manufacturer::model()->findByPk($field->manufacturerField->manufacturer_id)
									->descendants()->findAll();
			} else {
				$this->_manufacturerFilter = Manufacturer::model()->findAll();
			}
		}
		return $this->_manufacturerFilter;
	}

	private $_categoryFilter = null;
	private function getCategoryFilter($field)
	{
		if ( $this->_categoryFilter === null ){
			if( $field->categoryField->category_id ){
				$this->_categoryFilter = Category::model()->findByPk($field->categoryField->category_id)
									->descendants()->findAll();
			} else {
				$this->_categoryFilter = Category::model()->findAll();
			}
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

		$Form = array(
			'attributes'    =>  array(
				'enctype' => 'multipart/form-data',
				'class' => 'well',
				'id' => "recordForm",
			),
			'activeForm'    =>  array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'id' => "recordForm",
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),
			'elements'      =>  array(
				'<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab">Поля</a></li>
						<li><a href="#tab2" data-toggle="tab">SEO</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab1">
							<p>'
			),
			'buttons'       =>  array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? 'Создать' : "Сохранить",
					'class' =>  "btn"
				),
			),
		);

		$productFields = $this->getProductFields();

		if ( $productFields ){
			foreach( $productFields as $field ){
               $Form['elements'][$field->alias] = TypeField::getFieldFormData($field->field_type);
				switch( $field->field_type ){
    				case TypeField::DATETIME :
						$Form['elements'][$field->alias] = $field->dateTimeField->getElementCForm();
					break;                    
					case TypeField::TEXT :
						$Form['elements'][$field->alias]['rows'] = $field->textField->rows;
					break;
    				case TypeField::LISTS :
						$Form['elements'][$field->alias]['items'] = CHtml::listData(ListItem::model()->findAll('list_id = :list_id',array(':list_id'=>$field->listField->list_id)), 'id', 'name');
						if ( $field->listField->is_multiple_select ){

							$selected = array();
							if ( $this->{$field->alias."Items"} ) {
								foreach( $this->{$field->alias."Items"} as $Item ){
									$selected[] = $Item->id;
								}
							}

                            $this->{$field->alias} = $selected;

							$Form['elements'][$field->alias]['multiple'] = true;
							$Form['elements'][$field->alias]['class'] = 'chzn-select';
						}
					break;

    				case TypeField::CATEGORIES :

						if( $field->categoryField->category_id ){
							$category = Category::model()
												->findByPk($field->categoryField->category_id)
												->descendants()->findAll();
						} else {
							$category = Category::model()->findAll();
						}

						$Form['elements'][$field->alias]['items'] = CHtml::listData($category, 'id', 'name');
                        if ( $field->categoryField->is_multiple_select ){
							$selected = array();
							if ( $this->{$field->alias} ) {
								foreach( $this->{$field->alias} as $Item ){
									$selected[] = $Item->id;
								}
							}

                            $this->{$field->alias} = $selected;

							$Form['elements'][$field->alias]['multiple'] = true;
							$Form['elements'][$field->alias]['class'] = 'chzn-select';
                        }
					break;
				case TypeField::MANUFACTURER :

						if( $field->manufacturerField->manufacturer_id ){
							$manufacturer = Manufacturer::model()
												->findByPk($field->manufacturerField->manufacturer_id)
												->descendants()->findAll();
						} else {
							$manufacturer = Manufacturer::model()->findAll();
						}

						$Form['elements'][$field->alias]['items'] = CHtml::listData($manufacturer, 'id', 'name');
                        if ( $field->manufacturerField->is_multiple_select ){
							$selected = array();
							if ( $this->{$field->alias} ) {
								foreach( $this->{$field->alias} as $item ){
									$selected[] = $item->id;
								}
							}

                            $this->{$field->alias} = $selected;

							$Form['elements'][$field->alias]['multiple'] = true;
							$Form['elements'][$field->alias]['class'] = 'chzn-select';
                        }
					break;
				}
			}
		}


		$Form['elements'][]="</p></div>";


		$Form['elements'][]='<div class="tab-pane" id="tab2"><p>';
		$Form['elements']['alias'] = array('type'=>'text','class'=>"span5",'maxlength' =>  255);
		$Form['elements']['title'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['keywords'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['description'] = array('type'=>'textarea','class'=>"span5",'rows'=>5);
		$Form['elements'][]="</p></div>";


		$Form['elements'][]='</div></div>';

		//print_r($Form);

		return new CForm($Form,$this);
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
                        if ($field->listField->is_multiple_select)
    						$relations[$field->alias.'Items'] = array(	self::MANY_MANY,
																		'ListItem', 'RecordsLists(record_id, list_item_id)',
																		'condition'=> $field->alias.'_ListItem.`product_id` = :product_id',
																		'params' => array(":product_id" => $this->getProductID() ),
																		'together'=>true
																	);
						else
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
                            $relations[$field->alias.'Category'] = array( self::BELONGS_TO,'Category', $field->alias );
                    break;

					case TypeField::MANUFACTURER :
                        if ($field->manufacturerField->is_multiple_select)
    						$relations[$field->alias] = array(	self::MANY_MANY,
																'Manufacturer', 'record_manufacturer(record_id, manufacturer_id)',
																'condition'=> $field->alias.'_manufacturer.`product_id` = :product_id',
																'params' => array(":product_id" => $this->getProductID() ),
																'together'=>true
															);
                        else
                            $relations[$field->alias.'Manufacturer'] = array( self::BELONGS_TO,'Manufacturer', $field->alias );
                    break;

    				case TypeField::DATETIME :
                        if ($field->dateTimeField->is_multiple_select)
    						$relations[$field->alias] = array( self::HAS_ONE,'RecordDatetime','record_id');

                    break;

				}
			}
		}

		return $relations;
	}

	public function beforeSave() {
		parent::beforeSave();

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
		        	case TypeField::DATETIME :
						$date = new DateTime($this->{$field->alias});

						switch ($field->dateTimeField->type) {
							case DateTimeField::DATETIME:
								$this->{$field->alias} = $date->format('Y-m-d H:m:00');
							break;
							case DateTimeField::DATE:
								$this->{$field->alias} = $date->format('Y-m-d 00:00:00');
							break;
							case DateTimeField::TIME:
								$this->{$field->alias} = $date->format('00-00-00 H:m:00');
							break;
						}
					break;
				}
			}
		}

		return true;
	}

	public function afterFind() {
		parent::afterFind();

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
		        	case TypeField::DATETIME:
                        
                        if ( !empty($this->{$field->alias}) ){
    						$date = new DateTime($this->{$field->alias});
    
    						if ( $field->dateTimeField->is_multiple_select ) {
    							echo 123;
    						} else {
                                $this->{$field->alias} = $field->dateTimeField->formatedDateTime($this->{$field->alias});
    
    						}
				        }

					break;
				}
			}
		}

		return true;
	}

	public function afterSave()
	{
		parent::afterSave();

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
									if ( !$RecordCategory->save() ) throw new CException("ERROR SEVE CATEGORIES");
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
												'integerOnly'=>true, 'allowEmpty'=> $field->is_mandatory));
						else
							$rules[] = array($field->alias, 'numerical','integerOnly'=>true,'allowEmpty'=> $field->is_mandatory );

					break;
    				case TypeField::IMAGE :
						$rules[] = array($field->alias, 'file', 'types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>true );
					break;
    				case TypeField::BOOLEAN :
						$rules[] = array($field->alias, 'numerical','integerOnly'=>true,'allowEmpty'=> $field->is_mandatory );
					break;
        			case TypeField::DATETIME :
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
					case TypeField::INTEGER;
						$criteria->compare($field->alias, $this->{$field->alias});
					break;
					case TypeField::PRICE;
						$criteria->compare($field->alias, $this->{$field->alias});
					break;
					case TypeField::MANUFACTURER;
						if( $field->manufacturerField->is_multiple_select ){
							$criteria->compare($field->alias.'.id', $this->{$field->alias});
						} else {
							$criteria->compare($field->alias, $this->{$field->alias});
						}
					break;
					case TypeField::CATEGORIES;
						if( $field->categoryField->is_multiple_select ){
							$criteria->compare($field->alias.'.id', $this->{$field->alias});
						} else {
							$criteria->compare($field->alias, $this->{$field->alias});
						}
					break;
					case TypeField::DATETIME && $this->{$field->alias};
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

		return new CActiveDataProvider($this,array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));;
	}

	public function beforeValidate() {
		parent::beforeValidate();
		foreach ($this->getProductFields() as $field) {

			switch( $field->field_type ){
				case TypeField::IMAGE;
					$this->{$field->alias} = CUploadedFile::getInstance($this,$field->alias);
				break;
			}

		}

		return true;
	}
}
