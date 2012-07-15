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
	public $Parent = 0;
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
			array('Status, Name', 'required'),
			array('lft, rgt, Level, Status, Parent', 'numerical', 'integerOnly'=>true),
			array('Alias, Name', 'length', 'max'=>255),
			array('Alias, Name', 'unique'),
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
			'ID' => 'ID',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'Level' => 'Level',
			'Status' => 'Статус',
			'Alias' => 'Alias',
			'Name' => 'Наименование',
			'Description' => 'Описание',
			'Parent' => 'Родитель',
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

	/**
	 * Возвращает массив пунктов меню для виджета CMenu
	 * Параметры, тип меню и глубина
	 * @param string $type
	 * @param int $depth
	 * @return array
	 */
	protected function getMenuItems($type, $depth = 1) {

		$criteria = new CDbCriteria();
		$criteria->condition = 'menu_type = :menu_type AND level <= :level AND level > 1';
		// увеличиваем глубину на +1, т.к. 1ый уровень это рут.
		$criteria->params = array(':menu_type'=>$type, ':level'=>$depth+1);
		$models = Page::model()->active()->findAll($criteria);

		$level = 2; // начинаем с второго уровня
		$result = array();
		foreach($models as $model) {
			if($model->level > $level) {
				$result[$model->level] = &$result[$level][count($result[$level])-1]['items'];
			}

			$result[$model->level][]=array(
				'label'=>$model->title,
				'url'=>($model->is_main == 1 ? array('/'.Yii::app()->defaultController) : array('pages/view', 'url'=>$model->url)),
				'items'=>array()
			);

			if(($model->lft+1) != $model->rgt) {
				current($result);
			}

			$level = $model->level;
		}

		if(isset($result[2]))
			return $result[2];
		else
			return array();
	}

}