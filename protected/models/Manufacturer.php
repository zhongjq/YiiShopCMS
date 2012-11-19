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
class Manufacturer extends CActiveRecord
{
	public $logoFile;
	public $oldLogoFile;
	public $isDeleteLogoFile;
	public $parentId = 0;

	public function __construct($scenario = 'add')
	{
		parent::__construct($scenario);

		Yii::setPathOfAlias('manufacturersfiles', Yii::getPathOfAlias('webroot.data.manufacturers') );
		Yii::setPathOfAlias('manufacturersurl', Yii::app()->baseUrl."/data/manufacturers/");
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'manufacturer';
	}

	public function rules()
	{
		return array(
			array('alias, name', 'required', 'on'=>'add, edit'),
			array('status, isDeleteLogoFile, lft, rgt, level, parentId', 'numerical', 'integerOnly'=>true),
    		array('alias, name', 'length', 'max'=>255),
			array('alias, name', 'unique'),
    		array('alias', 'match', 'pattern' => '/^[A-Za-z0-9_-]+$/u','message' => Yii::t("manufacturers",'Alias contains invalid characters.')),
			array('title,keywords,description', 'safe'),
            array('logo', 'file', 'types'=>'jpg, gif, png', 'maxSize' => 1048576, 'allowEmpty'=>true,'safe'=>true ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, alias, name, description', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'			=>	Yii::t("AdminModule.main",'ID'),
			'lft'			=> 'Lft',
			'rgt'			=> 'Rgt',
			'level'			=> 'Level',
    		'status'		=>	Yii::t("manufacturers",'Status'),
			'alias'			=>	Yii::t("manufacturers",'Alias'),
			'name'			=>	Yii::t("manufacturers",'Name'),
			'description'	=>	Yii::t("manufacturers",'Description'),
			'logo'			=>	Yii::t("manufacturers",'Logo'),
			'isDeleteLogoFile' => Yii::t("manufacturers",'Delete an existing logo?'),
			'parentId' => Yii::t("manufacturers",'Parent'),
		);
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
				'id'=>'ManufacturerForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'id' => "ManufacturerForm",
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),

    		'elements'=>array(
				'<ul class="nav nav-tabs" data-tabs="tabs">
					<li class="active"><a data-toggle="tab" href="#p">'.Yii::t('manufacturers','Primary').'</a></li>
					<li><a data-toggle="tab" href="#seo">'.Yii::t('manufacturers','SEO').'</a></li>
				</ul>',
				'<div class="tab-content">',
					'<div class="tab-pane active" id="p">',
						'status'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'parentId'=>array(
							'type'  =>  'dropdownlist',
							'items' =>  CHtml::listData(Manufacturer::model()->findAll(array(
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
						'logo'=>array(
							'type'=>'file',
							'class'=>'input-file',
							'safe'=>true
						),

					'</div>',
					'<div class="tab-pane" id="seo">',
						'title'=>array(
							'type'=>'text'
						),
				    	'keywords'=>array(
							'type'=>'textarea',
							'rows'=>5
						),
				    	'description'=>array(
							'type'=>'textarea',
							'rows'=>5
						),
					'</div>',
				'</div>'
    		),
    		'buttons'=>array(
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  $this->isNewRecord ? Yii::t("main",'Add') : Yii::t("main",'Save'),
					'class' =>  "btn"
				),
			),
        );

		// если есть логотип
		if ( $this->logo ){
			$file = Yii::getPathOfAlias('manufacturersurl').'/'.$this->logo;
			$form['elements']['isDeleteLogoFile'] = array(
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
			$this->logo = $this->oldLogoFile;

			// если стоит галка удалить логотип
			if ( $this->isDeleteLogoFile ){
				if ( $this->deleteLogoFile() ) $this->logo = null;
			}

			if ( $this->logoFile ){
				// если загружается новый файл то удаляем старый
				if ( $this->deleteLogoFile() )
					$this->logo = null;
				else
					throw new CException("Не могу удалить файл");
				// получаем новый
				$this->logo = Controller::translit($this->name).'.'.$this->logoFile->getExtensionName();
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

		if ( $this->logoFile ){
			$dirName = Yii::getPathOfAlias('manufacturersfiles');

			if ( !is_dir($dirName) ){
				@mkdir($dirName,777,true);
			}
			$file = $dirName.DIRECTORY_SEPARATOR.$this->logo;
			$this->logoFile->saveAs($file);

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
		if ( $this->logo ){
			$nameFileLogo = Yii::getPathOfAlias('manufacturersfiles').DIRECTORY_SEPARATOR.$this->logo;

			return (is_file($nameFileLogo) && is_writable($nameFileLogo) && unlink($nameFileLogo) ) ? true : false;
		}
		return true;
	}

	public static function getMenuArray($manufacturers) {
		$return = array();

		if ( !empty($manufacturers) )
			foreach ($manufacturers as &$manufacturer) {
				$return[] = array(	'label'     =>  CHtml::encode($manufacturer->name),
									'url'       =>  array('manufacturer/view','alias'=>$manufacturer->alias),
									'active'    =>  CHttpRequest::getParam('alias') == $manufacturer->alias,
								  );
			}

        return $return;
	}


}