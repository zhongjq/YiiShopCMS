<?php

/**
 * This is the model class for table "Manufacturers".
 *
 * The followings are the available columns in table 'Manufacturers':
 * @property integer $ID
 * @property integer $Status
 * @property string $Alias
 * @property string $Name
 * @property string $Desctiprion
 */
class Manufacturers extends CActiveRecord
{
	public $PathLogo; 
	public $Logo;
	
	public function __construct($scenario = 'insert') {
		parent::__construct($scenario);
		
		Yii::setPathOfAlias('manufacturersfiles', Yii::getPathOfAlias('webroot')."/data/manufacturers/");
	}

		/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Manufacturers the static model class
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
		return 'Manufacturers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Alias, Name', 'required', 'on'=>'add, edit'),
			array('Status', 'numerical', 'integerOnly'=>true),
    		array('Alias, Name', 'length', 'max'=>255),
			array('Alias, Name', 'unique'),
    		array('Alias', 'match', 'pattern' => '/^[A-Za-z0-9]+$/u',
				    'message' => Yii::t("manufacturers",'Alias contains invalid characters.')),            
			array('Description', 'safe'),
            array('Logo', 'file', 'types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>true ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, Status, Alias, Name, Description', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
    		'Status'		=>	Yii::t("manufacturers",'Status'),
			'Alias'			=>	Yii::t("manufacturers",'Alias'),
			'Name'			=>	Yii::t("manufacturers",'Name'),
			'Description'	=>	Yii::t("manufacturers",'Description'),
			'Logo'			=>	Yii::t("manufacturers",'Logo'),
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
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Alias',$this->Alias,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Description',$this->Desctiprion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    // форма в формате CForm
    public function getArrayCForm(){
    	return array(
    		'attributes' => array(
				'enctype' => 'multipart/form-data',
				'class' => 'well',
				'id'=>'ManufacturersForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'id' => "ManufacturersForm",
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),

    		'elements'=>array(
        		'Status'=>array(
        			'type'=>'checkbox',
    				'layout'=>'{input}{label}{error}{hint}',
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
        		'Logo'=>array(
    				'type'=>'file',
					'class'=>'input-file'
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