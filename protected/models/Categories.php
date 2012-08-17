<?php

/**
 * This is the model class for table "Categories".
 *
 * The followings are the available columns in table 'Categories':
 * @property integer $ID
 * @property integer $lft
 * @property integer $rgt
 * @property integer $Level
 * @property integer $Status
 * @property string $Alias
 * @property string $Name
 * @property string $Description
 */
class Categories extends CActiveRecord
{
	public $ParentID = 0;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Categories the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, Alias', 'required', 'on'=> 'add, edit'),
			array('lft, rgt, Level, Status, ParentID', 'numerical', 'integerOnly'=>true),
			array('Alias, Name', 'length', 'max'=>255),
			array('Alias, Name', 'unique'),
    		array('Alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u',
				    'message' => Yii::t("categories",'Alias contains invalid characters.')),            
			array('Description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, lft, rgt, Level, Status, Alias, Name, Description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID'			=>	Yii::t("AdminModule.main",'ID'),
			'lft'			=> 'Lft',
			'rgt'			=> 'Rgt',
			'Level'			=> 'Level',
			'Status'		=>	Yii::t("categories",'Status'),
			'Alias'			=>	Yii::t("categories",'Alias'),
			'Name'			=>	Yii::t("categories",'Name'),
			'Description'	=>	Yii::t("categories",'Description'),
			'ParentID'		=>	Yii::t("categories",'Parent'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);
		$criteria->compare('Level',$this->Level);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Alias',$this->Alias,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Description',$this->Description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function behaviors()
	{
		return array(
			'NestedSetBehavior'=>array(
				'class'				=>	'application.extensions.nestedset.NestedSetBehavior',
				'leftAttribute'		=>	'lft',
				'rightAttribute'	=>	'rgt',
				'levelAttribute'	=>	'Level',
				'hasManyRoots'      =>  true
			),
		);
	}

	public function getListCategories(){
		$return = array();

		$Categories = Categories::model()->findAll(array('order'=>'lft'));
		foreach($Categories as $Category){
			$return[$Category->ID] = $Category->Name;
		}

		return $return;
	}

	public static function getMenuItems($items, $start = 0) {
        
		$return = array();
		$SizeMenu = sizeof($items);
		for( $i = $start; $i < $SizeMenu; $i++ ){
            $return[$i] = array(	'label'     =>  CHtml::encode($items[$i]->Name),
									'url'       =>  array('/categories/view/','Alias'=>$items[$i]->Alias),
									'active'    =>  CHttpRequest::getParam('Alias') == $items[$i]->Alias,
						      );
			
			$cn = $items[$i]->rgt - $items[$i]->lft;
			if ( $cn != 1 ){
				$return[$i]['items'] = Categories::getMenuItems($items,$i+1);
				$i = ceil( $cn/2 );
			}
		}
                
        return $return;
	}
	
	public static function getMenuArray($items) {
		return Categories::getMenuItems($items);
	}
    
    // форма в формате CForm
	public function getArrayCForm(){
		return array(
    		'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'CategoryForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "CategoryForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

    		'elements'=>array(
        		'Status'=>array(
        			'type'=>'checkbox',
    				'layout'=>'{input}{label}{error}{hint}',
    			),
    			'ParentID'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  CHtml::listData(Categories::model()->findAll(array(
    															'order'=>'lft',
																'condition'=>'ID != :ID',
																'params'=>array(':ID'=> $model->ID ? $model->ID : 0 )
															)
															), 'ID', 'Name'),
					'empty'=>  '',
				),                
    			'Name'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'Alias'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'Description'=>array(
    				'type'=>'textarea',
    				'rows'=>5
    			),
    		),
    		'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? Yii::t("main",'Add') : Yii::t("main",'Save'),
					'class' =>  "btn"
				),
			),            
        );
	}    
    
}