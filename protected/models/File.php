<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property string $id
 * @property string $product_id
 * @property string $record_id
 * @property string $name
 * @property string $disc_name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Product $product
 */
class File extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return File the static model class
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
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, record_id, name, disc_name', 'required'),
			array('product_id, record_id', 'length', 'max'=>10),
			array('name, disc_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, record_id, name, disc_name, description', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO, 'Product', 'product_id', 'together'=>true ),
		);
	}
    
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'record_id' => 'Record',
			'name' => 'Name',
			'disc_name' => 'Disc Name',
			'description' => 'Description',
		);
	}

	public function getFolder(){
        return Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR.$this->product_id.DIRECTORY_SEPARATOR.$this->record_id.DIRECTORY_SEPARATOR;
	}
    
    public function getUrl(){
        return Yii::app()->baseUrl.DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR.$this->product_id.DIRECTORY_SEPARATOR.$this->record_id.DIRECTORY_SEPARATOR.$this->disc_name ;
	}
    
    public function afterDelete(){        
        @unlink( $this->getFolder().DIRECTORY_SEPARATOR.$this->disc_name );
        return true;
    }
}