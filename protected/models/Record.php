<?php

class Record extends CActiveRecord
{
	private $_productId = null;
	private $_product = null;
	private $_productFields = null;
	private $_tableFields = null;

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
				throw new CException("ID NOT ProductID");
		}

		if ( $this->_productFields === null && $update === false )
			$this->_productFields = ProductField::model()
										->with('stringField','textField','integerField','priceField','listField','categoryField')
										->findAll('product_id=:product_id',array(':product_id'=>$this->getProductID()));



		return $this->_productFields;
	}

	public function getTableFields($update = false)
	{

		if ( $this->_tableFields === null && $update === false ){
			$productFields = $this->getProductFields();

			if ( $productFields ){
				foreach( $productFields as $Field ){
					if( $Field->IsColumnTable )

						switch( $Field->field_type ){
							case TypeField::LISTS :
								if ($Field->listField->is_multiple_select)
									$this->TableFields[] = array(
										'name' => $Field->alias,
										'value' => '$data->getRecordItems("'.$Field->alias.'Items")'
									);
								else
									$this->TableFields[] = array(
										'name'	=> $Field->alias,
										'value' => 'isset($data->'.$Field->alias.'Item) ? $data->'.$Field->alias.'Item->name : null'
									);
							break;
							case TypeField::CATEGORIES :
                                if ($Field->categoryField->is_multiple_select)
                                    $this->TableFields[] = array(
    									'name' => $Field->alias,
										'value' => '$data->getRecordCategory("'.$Field->alias.'")'
									);
                                else
									$this->TableFields[] = array(
    									'name'	=> $Field->alias,
										'value' => 'isset($data->'.$Field->alias.'Category) ? $data->'.$Field->alias.'Category->name : null'
									);
							break;
							default:
								$this->TableFields[] = $Field->alias;
							break;
						}


				}
			}
		}

		return $this->TableFields;
	}

    public function getRecordItems($Name, $sSep = ', ')
	{

       $aRes = array();
       foreach ($this->{$Name} as $Item) {
          $aRes[] = $Item->Name;
       }

       return implode($sSep, $aRes);
    }

    public function getRecordCategory($name, $sSep = ', ')
	{
       $aRes = array();
       
       foreach ($this->{$name} as $Item) {
          $aRes[] = $Item->name;
       }

       return implode($sSep, $aRes);
    }

	public function setGoodsAttributes()
	{
		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$this->setAttribute($Field->Alias,$Field->Alias);
			}
		}
	}

	public function getMotelCForm()
	{

		$Form = array(
			'attributes'    =>  array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id' => "GoodsForm",
			),
			'activeForm'    =>  array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "GoodsForm",
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

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
                $Form['elements'][$Field->alias] = TypeField::$Fields[$Field->field_type]['form'];
				switch( $Field->field_type ){
					case TypeField::TEXT :
						$Form['elements'][$Field->alias]['rows'] = $Field->textField->rows;
					break;
    				case TypeField::LISTS :
						$Form['elements'][$Field->alias]['items'] = CHtml::listData(ListItem::model()->findAll('list_id = :list_id',array(':list_id'=>$Field->listField->list_id)), 'id', 'name');
						if ( $Field->listField->is_multiple_select ){

							$selected = array();
							if ( $this->{$Field->alias."Items"} ) {
								foreach( $this->{$Field->alias."Items"} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$Field->alias} = $selected;

							$Form['elements'][$Field->alias]['multiple'] = true;
							$Form['elements'][$Field->alias]['class'] = 'chzn-select';
						}
					break;

    				case TypeField::CATEGORIES :
						$Form['elements'][$Field->alias]['items'] = CHtml::listData(Category::model()->findAll(), 'id', 'name');
                        if ( $Field->categoryField->is_multiple_select ){
							$selected = array();
							if ( $this->{$Field->alias} ) {
								foreach( $this->{$Field->alias} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$Field->alias} = $selected;

							$Form['elements'][$Field->alias]['multiple'] = true;
							$Form['elements'][$Field->alias]['class'] = 'chzn-select';
                        }
					break;
				}
			}
		}


		$Form['elements'][]="</p></div>";


		$Form['elements'][]='<div class="tab-pane" id="tab2"><p>';
		$Form['elements']['Alias'] = array('type'=>'text','class'=>"span5",'maxlength' =>  255);
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

        $ProductFields = $this->getProductFields();
    	if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				switch( $Field->field_type ){
					case TypeField::LISTS :
                        if ($Field->listField->is_multiple_select)
    						$relations[$Field->alias.'Items'] = array(	self::MANY_MANY,
																		'ListItem', 'RecordsLists(record_id, list_item_id)',
																		'on' => 'product_id = ' .$this->getProductID()

																	);
						else
                            $relations[$Field->alias.'Item'] = array( self::BELONGS_TO,'ListItem', $Field->alias );
                    break;
					case TypeField::CATEGORIES :
                        if ($Field->categoryField->is_multiple_select)
    						$relations[$Field->alias] = array(	self::MANY_MANY,
																'Category', 'record_category(record_id, category_id)',
																//'on' => 'product_id = ' .$this->getProductID()

															);
                        else
                            $relations[$Field->alias.'Category'] = array( self::BELONGS_TO,'Category', $Field->alias );                                            
                    break;
				}
			}
		}

		return $relations;
	}

	public function afterSave()
	{
		parent::afterSave();

        $PostData = $_POST[$this->Product->Alias];

        $ProductFields = $this->getProductFields();
    	if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				switch( $Field->field_type ){
					case TypeField::LISTS :
                        if ($Field->listField->is_multiple_select){

							RecordsLists::model()->deleteAll('product_id = :ProductID AND RecordID = :RecordID',array(":ProductID"=> $this->getProductID(),':RecordID'=> $this->ID));

							if ( isset($PostData[$Field->Alias]) ){
								foreach ($PostData[$Field->Alias] as $ListItemID) {
									$RecordsLists = new RecordsLists();
									$RecordsLists->ProductID = $this->getProductID();
									$RecordsLists->RecordID = $this->ID;
									$RecordsLists->ListItemID = $ListItemID;
									if ( !$RecordsLists->save() ) throw new CException("ERROR SEVE LISTS");
								}
							}
						}
                    break;
					case TypeField::CATEGORIES :

						RecordsCategories::model()->deleteAll('ProductID = :ProductID AND RecordID = :RecordID',array(":ProductID"=> $this->getProductID(),':RecordID'=> $this->ID));

						if ( isset($PostData[$Field->Alias]) ){
							foreach ($PostData[$Field->Alias] as $CategoryID) {
								$RecordsCategories = new RecordsCategories();
								$RecordsCategories->ProductID = $this->getProductID();
								$RecordsCategories->RecordID = $this->ID;
								$RecordsCategories->CategoryID = $CategoryID;
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

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				if ( $Field->is_mandatory ) $required[] = $Field->alias;

				switch( $Field->field_type ){
					case TypeField::TEXT :
						$safe[] = $Field->alias;
						$rules[] = array($Field->alias,'length','min'=> $Field->textField->min_length,'max'=>$Field->textField->max_length,'allowEmpty'=>true );
					break;
					case TypeField::STRING :
						$safe[] = $Field->alias;
						$rules[] = array($Field->alias,'length','min'=> $Field->stringField->min_length,'max'=>$Field->stringField->max_length,'allowEmpty'=>true );
					break;
					case TypeField::INTEGER :
						$rules[] = array($Field->alias, 'numerical', 'integerOnly'=>true,'min'=> $Field->integerField->min_value ,'max'=>$Field->integerField->max_value ,'allowEmpty'=>true);
					break;
					case TypeField::PRICE :
						$rules[] = array($Field->alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,2}?\s*$/',
											'message' => Yii::t("products",'Price has the wrong format (eg 10.50).')
										);

						$rules[] = array($Field->alias, 'numerical', 'max'=>$Field->priceField->max_value ,'allowEmpty'=>true);

					break;
    				case TypeField::LISTS :
						if ($Field->listField->is_multiple_select)
							$rules[] = array($Field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true, 'allowEmpty'=>false
											));
						else
							$rules[] = array($Field->alias, 'numerical', 'integerOnly'=>true,'allowEmpty'=>true);
					break;
    				case TypeField::CATEGORIES :
							$rules[] = array($Field->alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true, 'allowEmpty'=>false
											));
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

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$labels[$Field->alias] = $Field->name;
			}
		}

		return $labels;
	}


}
