<?php

class Record extends CActiveRecord
{
	private $_productId = null;
	private $_product = null;
	private $_productFields = null;
	private $_tableFields = null;
    private $_searchFields = null;
    public $data = null;

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
		if ( $this->_product === null ) {
			$this->_product = Product::model()->find('alias = :alias',array(':alias'=> get_class($this)) );

			if ( $this->_product )
				$this->setProductID($this->_product->id);
			else
				throw new CException("ID NOT product_id");
		}

		if ( $this->_productFields === null && $update === false )
			$this->_productFields = ProductField::model()
										->with('stringField','textField','integerField','priceField','listField','categoryField','manufacturerField')
										->findAll('product_id=:product_id',array(':product_id'=>$this->getProductID()));



		return $this->_productFields;
	}

	public function getTableFields($update = false)
	{

		if ( $this->_tableFields === null && $update === false ){
			$productFields = $this->getProductFields();

			if ( $productFields ){
				foreach( $productFields as $field ){
					if( $field->is_column_table )
						$f['name'] = $field->alias;

						switch( $field->field_type ){
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
							break;
							case TypeField::MANUFACTURER:
								if ($field->manufacturerField->is_multiple_select)
									$f['value'] = '$data->getRecordManufacturer("'.$field->alias.'")';
								else
									$f['value'] = 'isset($data->'.$field->alias.'Manufacturer) ? $data->'.$field->alias.'Manufacturer->name : null';

							break;
						}

						if ( $field->is_filter == 0 && !isset($f['filter']) ) $f['filter'] = false;

						$this->_tableFields[] = $f;
						unset($f);
				}
			}
		}

		return $this->_tableFields;
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
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id' => "recordForm",
			),
			'activeForm'    =>  array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "recordForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
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
                $Form['elements'][$field->alias] = TypeField::$Fields[$field->field_type]['form'];
				switch( $field->field_type ){
					case TypeField::TEXT :
						$Form['elements'][$field->alias]['rows'] = $field->textField->rows;
					break;
    				case TypeField::LISTS :
						$Form['elements'][$field->alias]['items'] = CHtml::listData(ListItem::model()->findAll('list_id = :list_id',array(':list_id'=>$field->listField->list_id)), 'id', 'name');
						if ( $field->listField->is_multiple_select ){

							$selected = array();
							if ( $this->{$field->alias."Items"} ) {
								foreach( $this->{$field->alias."Items"} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$field->alias} = $selected;

							$Form['elements'][$field->alias]['multiple'] = true;
							$Form['elements'][$field->alias]['class'] = 'chzn-select';
						}
					break;

    				case TypeField::CATEGORIES :
						$Form['elements'][$field->alias]['items'] = CHtml::listData(Category::model()->findAll(), 'id', 'name');
                        if ( $field->categoryField->is_multiple_select ){
							$selected = array();
							if ( $this->{$field->alias} ) {
								foreach( $this->{$field->alias} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$field->alias} = $selected;

							$Form['elements'][$field->alias]['multiple'] = true;
							$Form['elements'][$field->alias]['class'] = 'chzn-select';
                        }
					break;
				case TypeField::MANUFACTURER :
						$Form['elements'][$field->alias]['items'] = CHtml::listData(Manufacturer::model()->findAll(), 'id', 'name');
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
		$Form['elements']['Title'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['Keywords'] = array('type'=>'textarea','class'=>"span5");
		$Form['elements']['Description'] = array('type'=>'textarea','class'=>"span5",'rows'=>5);
		$Form['elements'][]="</p></div>";


		$Form['elements'][]='</div></div>';

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
																		'on' => 'product_id = ' .$this->getProductID()

																	);
						else
                            $relations[$field->alias.'Item'] = array( self::BELONGS_TO,'ListItem', $field->alias );
                    break;
					case TypeField::CATEGORIES :
                        if ($field->categoryField->is_multiple_select)
    						$relations[$field->alias] = array(	self::MANY_MANY,
																'Category', 'record_category(record_id, category_id)',
																'on' => 'product_id = ' .$this->getProductID()

															);
                        else
                            $relations[$field->alias.'Category'] = array( self::BELONGS_TO,'Category', $field->alias );
                    break;


					case TypeField::MANUFACTURER :
                        if ($field->manufacturerField->is_multiple_select)
    						$relations[$field->alias] = array(	self::MANY_MANY,
																'Manufacturer', 'record_manufacturer(record_id, manufacturer_id)',
																'on' => 'product_id = ' .$this->getProductID()

															);
                        else
                            $relations[$field->alias.'Manufacturer'] = array( self::BELONGS_TO,'Manufacturer', $field->alias );
                    break;


				}
			}
		}

		return $relations;
	}

	public function afterSave1()
	{
		parent::afterSave();

        $postData = $_POST[$this->_product->alias];

        $productFields = $this->getProductFields();
    	if ( $productFields ){
			foreach( $productFields as $field ){
				switch( $field->field_type ){
					case TypeField::LISTS :
                        if ($field->listField->is_multiple_select){

							RecordList::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->ID));

							if ( isset($PostData[$field->alias]) ){
								foreach ($PostData[$field->alias] as $list_item_id) {
									$RecordsLists = new RecordsLists();
									$RecordsLists->product_id = $this->getProductID();
									$RecordsLists->record_id = $this->ID;
									$RecordsLists->list_item_id = $list_item_id;
									if ( !$RecordsLists->save() ) throw new CException("ERROR SEVE LISTS");
								}
							}
						}
                    break;

					case TypeField::CATEGORIES :

						RecordCategory::model()->deleteAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->getProductID(),':record_id'=> $this->ID));

						if ( isset($PostData[$field->alias]) ){
							foreach ($PostData[$field->alias] as $category_id) {
								$RecordsCategories = new RecordsCategories();
								$RecordsCategories->product_id = $this->getProductID();
								$RecordsCategories->record_id = $this->ID;
								$RecordsCategories->category_id = $category_id;
								if ( !$RecordsCategories->save() ) throw new CException("ERROR SEVE CATEGORIES");
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

	public function search(){
		$criteria = new CDbCriteria;

		foreach ($this->getProductFields() as $field) {
			if ( $field->is_filter ){
				switch( $field->field_type ){
					case TypeField::INTEGER;
						$criteria->compare($field->alias, $_GET[get_class($this)][$field->alias]);
					break;
					case TypeField::PRICE;
						$criteria->compare($field->alias, $_GET[get_class($this)][$field->alias]);
					break;
				}
			}
		}

		return new CActiveDataProvider($this,array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));;
	}
}
