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
class Category extends CActiveRecord
{
	public $parentId = 0;
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
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias', 'required', 'on'=> 'add, edit'),
			array('lft, rgt, level, status, parentId', 'numerical', 'integerOnly'=>true),
			array('alias, name', 'length', 'max'=>255),
			array('alias, name', 'unique'),
    		array('alias', 'match', 'pattern' => '/^[A-Za-z0-9-]+$/u',
				    'message' => Yii::t("categories",'Alias contains invalid characters.')),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, lft, rgt, level, status, alias, name, description', 'safe', 'on'=>'search'),
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
			'id'			=>	Yii::t("AdminModule.main",'ID'),
			'lft'			=> 'Lft',
			'rgt'			=> 'Rgt',
			'level'			=> 'Level',
			'status'		=>	Yii::t("categories",'Status'),
			'alias'			=>	Yii::t("categories",'Alias'),
			'name'			=>	Yii::t("categories",'Name'),
			'description'	=>	Yii::t("categories",'Description'),
			'parentId'		=>	Yii::t("categories",'Parent'),
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
				'class'				=>	'ext.nestedset.NestedSetBehavior',
				'leftAttribute'		=>	'lft',
				'rightAttribute'	=>	'rgt',
				'levelAttribute'	=>	'level',
				'hasManyRoots'      =>  true
			),
		);
	}

	public function getListCategories(){
		$return = array();

		$categories = Category::model()->findAll(array('order'=>'lft'));
		foreach($categories as $category){
			$return[$category->id] = $Category->name;
		}

		return $return;
	}

	public static function getMenuItems($items, $start = 0) {

		$return = array();
		$sizeMenu = sizeof($items);
        if ( $sizeMenu > 0 )
    		for( $i = $start; $i < $sizeMenu; $i++ ){
                $return[$i] = array(	'label'     =>  CHtml::encode($items[$i]->name),
    									'url'       =>  array('/category/view','alias'=>$items[$i]->alias),
    									'active'    =>  CHttpRequest::getParam('alias') == $items[$i]->alias,
    						      );
    
    			$cn = $items[$i]->rgt - $items[$i]->lft;
    			if ( $cn != 1 ){
    				$return[$i]['items'] = Category::getMenuItems($items,$i+1);
    				$i = ceil( $cn/2 );
    			}
    		}

        return $return;
	}

	public static function getMenuArray($items) {        
		return ( is_array($items) && !empty($items) ) ? Category::getMenuItems($items) : null;
	}

    // форма в формате CForm
	public function getArrayCForm(){
		return array(
    		'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "categoryForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

    		'elements'=>array(
        		'status'=>array(
        			'type'=>'checkbox',
    				'layout'=>'{input}{label}{error}{hint}',
    			),
    			'parentId'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  CHtml::listData(Category::model()->findAll(array(
																'select'=>"id,name",
    															'order'=>'lft',
																'condition'=>'id != :id',
																'params'=>array(':id' => $this->id ? $this->id : 0 )
															)
															), 'id', 'name'),
					'empty'=>  '',
				),
    			'name'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'alias'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'description'=>array(
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