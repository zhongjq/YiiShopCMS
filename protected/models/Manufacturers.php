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
	public $LogoFile;
	public $OldLogoFile;
	public $IsDeleteLogoFile;

	public function __construct($scenario = 'add')
	{
		parent::__construct($scenario);

		Yii::setPathOfAlias('manufacturersfiles', Yii::getPathOfAlias('webroot')."/data/manufacturers/");
		Yii::setPathOfAlias('manufacturersurl', Yii::app()->baseUrl."/data/manufacturers/");
	}

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

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Alias, Name', 'required', 'on'=>'add, edit'),
			array('Status, IsDeleteLogoFile', 'numerical', 'integerOnly'=>true),
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

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
    		'Status'		=>	Yii::t("manufacturers",'Status'),
			'Alias'			=>	Yii::t("manufacturers",'Alias'),
			'Name'			=>	Yii::t("manufacturers",'Name'),
			'Description'	=>	Yii::t("manufacturers",'Description'),
			'Logo'			=>	Yii::t("manufacturers",'Logo'),
			'IsDeleteLogoFile' => Yii::t("manufacturers",'Delete an existing logo?'),
		);
	}

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
    public function getArrayCForm()
	{
    	$form = array(
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

		// если есть логотип
		if ( $this->Logo ){
			$file = Yii::getPathOfAlias('manufacturersurl').'/'.$this->Logo;
			$form['elements']['IsDeleteLogoFile'] = array(
        		'type'=>'checkbox',
    			'layout'=>'{input}{label}{error}{hint}',
				'hint'=> CHtml::link('просмотреть',  $file , array("id"=>"fancy-link")),
    		);
		}

		return $form;
	}

	// до сохранения
	protected function beforeSave()
	{
		if (parent::beforeSave() ){
			$this->Logo = $this->OldLogoFile;

			// если стоит галка удалить логотип
			if ( $this->IsDeleteLogoFile ){
				if ( $this->deleteLogoFile() ) $this->Logo = null;
			}

			if ( $this->LogoFile ){
				// если загружается новый файл то удаляем старый
				if ( $this->deleteLogoFile() )
					$this->Logo = null;
				else
					throw new CException("Не могу удалить файл");
				// получаем новый
				$this->Logo = Controller::translit($this->Name).'.'.$this->LogoFile->getExtensionName();
			}

			return true;
		} else {
			return false;
		}
	}

	// после сохранения
    protected function afterSave()
	{
		parent::afterSave();

		if ( $this->LogoFile ){
			$file = Yii::getPathOfAlias('manufacturersfiles').'/'.$this->Logo;
			$this->LogoFile->saveAs($file);
			/*
			Yii::import('ext.wideimage.WideImage');
			WideImage::load($file)->resize(50, 30)->saveToFile('small.jpg');
			WideImage::load($file)->crop('center', 'center', 90, 50)->saveToFile('small.png');
			*/
		}

	}

	// после удаления
	protected function beforeDelete()
	{
		if (parent::beforeDelete()){
			if (!$this->deleteLogoFile()) throw new CException("Не могу удалить файл");
			return true;
		} else {
			return false;
		}
	}

	// удаление логотипа
	public function deleteLogoFile(){
		if ( $this->Logo ){
			$NameFileLogo = Yii::getPathOfAlias('manufacturersfiles').DIRECTORY_SEPARATOR.$this->Logo;

			return (is_file($NameFileLogo) && is_writable($NameFileLogo) && unlink($NameFileLogo) ) ? true : false;
		}
		return true;
	}

	public static function getMenuArray($Manufacturers) {

		$return = array();
		foreach ($Manufacturers as $Manufacturer) {
            $return[] = array(	'label'     =>  CHtml::encode($Manufacturer->Name),
								'url'       =>  array('manufacturers/view','alias'=>$Manufacturer->Alias),
								'active'    =>  CHttpRequest::getParam('alias') == $Manufacturer->Alias,
						      );
		}

        return $return;
	}

}