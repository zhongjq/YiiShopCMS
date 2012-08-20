<?php

class Goods extends CActiveRecord
{
	private $ProductID = null;
	private $Product = null;
	private $ProductsFields = null;
	private $TableFields = null;

	public function setProductID($v)
	{
		$this->ProductID = $v;
	}

	public function getProductID()
	{
		return $this->Product->ID;
	}

	public function getProductFields($update = false)
	{
		if ( $this->Product === null ) {
			$this->Product = Products::model()->find('Alias = :Alias',array(':Alias'=> get_class($this)) );

			if ( $this->Product )
				$this->setProductID($this->Product->ID);
			else
				throw new CException("ID NOT ProductID");
		}

		if ( $this->ProductsFields === null && $update === false )
			$this->ProductsFields = ProductsFields::model()
										->with('StringFields','TextFields','IntegerFields','PriceFields','ListFields')
										->findAll('ProductID=:ProductID',array(':ProductID'=>$this->getProductID()));



		return $this->ProductsFields;
	}

	public function getTableFields($update = false)
	{

		if ( $this->TableFields === null && $update === false ){
			$ProductFields = $this->getProductFields();

			if ( $ProductFields ){
				foreach( $ProductFields as $Field ){
					if( $Field->IsColumnTable )

						switch( $Field->FieldType ){
							case TypeFields::LISTS :
								if ($Field->ListFields->IsMultipleSelect)
									$this->TableFields[] = array(
										'name' => $Field->Alias,
										'value' => '$data->getRecordItems("'.$Field->Alias.'Items")'
									);
								else
									$this->TableFields[] = array(
										'name'	=> $Field->Alias,
										'value' => 'isset($data->'.$Field->Alias.'Item) ? $data->'.$Field->Alias.'Item->Name : null'
									);
							break;
							case TypeFields::CATEGORIES :
									$this->TableFields[] = array(
										'name' => $Field->Alias,
										'value' => '$data->getRecordCategory("'.$Field->Alias.'")'
									);
							break;
							default:
								$this->TableFields[] = $Field->Alias;
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
                $Form['elements'][$Field->Alias] = TypeFields::$Fields[$Field->FieldType]['form'];
				switch( $Field->FieldType ){
					case TypeFields::TEXT :
						$Form['elements'][$Field->Alias]['rows'] = $Field->TextFields->Rows;
					break;
    				case TypeFields::LISTS :
						$Form['elements'][$Field->Alias]['items'] = CHtml::listData(ListsItems::model()->findAll('ListID = :ListID',array(':ListID'=>$Field->ListFields->ListID)), 'ID', 'Name');
						if ( $Field->ListFields->IsMultipleSelect ){

							$selected = array();
							if ( $this->{$Field->Alias."Items"} ) {
								foreach( $this->{$Field->Alias."Items"} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$Field->Alias} = $selected;

							$Form['elements'][$Field->Alias]['multiple'] = true;
							$Form['elements'][$Field->Alias]['class'] = 'chzn-select';
						}
					break;

    				case TypeFields::CATEGORIES :
						$Form['elements'][$Field->Alias]['items'] = CHtml::listData(Category::model()->findAll(), 'ID', 'Name');

							$selected = array();
							if ( $this->{$Field->Alias} ) {
								foreach( $this->{$Field->Alias} as $Item ){
									$selected[] = $Item->ID;
								}
							}

                            $this->{$Field->Alias} = $selected;

							$Form['elements'][$Field->Alias]['multiple'] = true;
							$Form['elements'][$Field->Alias]['class'] = 'chzn-select';

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
				switch( $Field->FieldType ){
					case TypeFields::LISTS :
                        if ($Field->ListFields->IsMultipleSelect)
    						$relations[$Field->Alias.'Items'] = array(	self::MANY_MANY,
																		'ListsItems', 'RecordsLists(RecordID, ListItemID)',
																		'on' => 'ProductID = ' .$this->getProductID()

																	);
						else
                            $relations[$Field->Alias.'Item'] = array( self::BELONGS_TO,'ListsItems',$Field->Alias );
                    break;
					case TypeFields::CATEGORIES :

    						$relations[$Field->Alias] = array(	self::MANY_MANY,
																		'Category', 'record_category(record_id, category_id)',
																		//'on' => 'ProductID = ' .$this->getProductID()

																	);
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
				switch( $Field->FieldType ){
					case TypeFields::LISTS :
                        if ($Field->ListFields->IsMultipleSelect){

							RecordsLists::model()->deleteAll('ProductID = :ProductID AND RecordID = :RecordID',array(":ProductID"=> $this->getProductID(),':RecordID'=> $this->ID));

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
					case TypeFields::CATEGORIES :

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
		$safe       = array('Title','Keywords','Description');
		$unique     = array("Alias");

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				if ( $Field->IsMandatory ) $required[] = $Field->Alias;

				switch( $Field->FieldType ){
					case TypeFields::TEXT :
						$safe[] = $Field->Alias;
						$rules[] = array($Field->Alias,'length','min'=> $Field->TextFields->MinLength,'max'=>$Field->TextFields->MaxLength,'allowEmpty'=>true );
					break;
					case TypeFields::STRING :
						$safe[] = $Field->Alias;
						$rules[] = array($Field->Alias,'length','min'=> $Field->StringFields->MinLength,'max'=>$Field->StringFields->MaxLength,'allowEmpty'=>true );
					break;
					case TypeFields::INTEGER :
						$rules[] = array($Field->Alias, 'numerical', 'integerOnly'=>true,'min'=> $Field->IntegerFields->MinValue ,'max'=>$Field->IntegerFields->MaxValue ,'allowEmpty'=>true);
					break;
					case TypeFields::PRICE :
						$rules[] = array($Field->Alias, 'match', 'pattern'=>'/^\s*[-+]?[0-9]*\.?[0-9]{1,2}?\s*$/',
											'message' => Yii::t("AdminModule.products",'Price has the wrong format (eg 10.50).')
										);

						$rules[] = array($Field->Alias, 'numerical', 'max'=>$Field->PriceFields->MaxValue ,'allowEmpty'=>true);

					break;
    				case TypeFields::LISTS :
						if ($Field->ListFields->IsMultipleSelect)
							$rules[] = array($Field->Alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
												'integerOnly'=>true, 'allowEmpty'=>false
											));
						else
							$rules[] = array($Field->Alias, 'numerical', 'integerOnly'=>true,'allowEmpty'=>true);
					break;
    				case TypeFields::CATEGORIES :
							$rules[] = array($Field->Alias, 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
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

		$rules[] = array('Alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u',
						'message' => Yii::t("AdminModule.products",'Field contains invalid characters.'));

		return $rules;
	}

	public function attributeLabels()
	{
		$labels = array();

		$ProductFields = $this->getProductFields();

		if ( $ProductFields ){
			foreach( $ProductFields as $Field ){
				$labels[$Field->Alias] = $Field->Name;
			}
		}

		return $labels;
	}


}
