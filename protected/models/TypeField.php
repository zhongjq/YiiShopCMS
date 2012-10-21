<?php

class TypeField
{
	const INTEGER = 1;
	const STRING = 2;
	const PRICE = 3;
	const TEXT = 4;
    const LISTS = 5;
    const CATEGORIES = 6;
    const MANUFACTURER = 7;
    const IMAGE = 8;
    const FILE = 9;
    const DOUBLE = 10;
    const BOOLEAN = 11;
    const DATETIME = 12;

	public static $TypeToIDFields = array(
		'integer'   => self::INTEGER,
		'string'    => self::STRING,
		'price'     => self::PRICE,
		'text'      => self::TEXT,
        'list'      => self::LISTS,
        'categories'=> self::CATEGORIES,
        'image'		=> self::IMAGE,
        'file'		=> self::FILE,
        'double'	=> self::DOUBLE,
        'boolean'	=> self::BOOLEAN,
        'datatime'  => self::DATETIME
	);

	public static $Fields = array(
		self::STRING    =>  array(
			'name'      =>  "Строка",
			'class'     =>  "StringField",
			'type'      =>  "string",
			'dbType'    =>  "varchar(255)",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  255,
			),
		),
		self::INTEGER   =>  array(
			'name'      =>  "Целое число",
			'type'      =>  "integer",
			'class'     =>  "IntegerField",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  11,
			),
		),
		self::DOUBLE   =>  array(
			'name'      =>  "Дробное число",
			'type'      =>  'double',
			'class'     =>  "DoubleField",
			'dbType'    =>  "float",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  11,
			),
		),
		self::TEXT   =>  array(
			'name'      =>  "Текст",
			'type'      =>  "text",
			'class'     =>  "TextField",
			'dbType'    =>  "text",
			'form'      =>  array(
				'type'  =>  'textarea',
			),
		),
		self::PRICE     => array(
			'name'      =>  "Цена",
			'type'      =>  "price",
			'class'     =>  "PriceField",
			'dbType'    =>  "decimal(9,2)",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  12,
			),
		),
		self::BOOLEAN     => array(
			'name'      =>  "Логический переключатель",
			'type'      =>  "boolean",
			'class'     =>  "BooleanField",
			'dbType'    =>  "tinyint(1)",
			'form'      =>  array(
				'type'=>'checkbox',
				'layout'=>'{input}{label}{error}{hint}',
			),
		),
    	self::LISTS     => array(
			'name'      =>  "Список",
			'type'      =>  "list",
			'class'     =>  "ListField",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
                'type'  =>  'dropdownlist',
				'empty' =>  '',
            ),
		),

        self::DATETIME  => array(
    		'name'      =>  "Дата/время",
			'type'      =>  "datetime",
            'class'     =>  "DateTimeField",
			'dbType'    =>  "datetime",
			'form'      =>  array(
				'type'		=> 'zii.widgets.jui.CJuiDatePicker',
				'language'	=> '',
             ),
		),

    	self::FILE      => array(
			'name'      =>  "Файл(ы)",
			'type'      =>  "file",
            'class'     =>  "FileField",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
                'type'  =>  'Files',
            ),
		),




        self::CATEGORIES     => array(
			'name'      =>  "Категория(и)",
			'type'      =>  "categories",
            'class'     =>  "CategoryField",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
                'type'  =>  'dropdownlist',
				'empty' =>  '',
            ),
		),
    	self::MANUFACTURER     => array(
			'name'      =>  "Производитель(и)",
			'type'      =>  "manufacturer",
            'class'     =>  "ManufacturerField",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
                'type'  =>  'dropdownlist',
				'empty' =>  '',
            ),
		),


	);



	public function __construct(){
		$this->fields = self::$Fields;
		$this->fields[self::DATETIME]['form']['language'] = Yii::app()->getLanguage();
	}


	public static function getFieldFormData($fieldId){
		$field = new TypeField();

		return $field->fields[$fieldId]['form'];
	}

	public static function getFieldsList(){
		$return = array();
		foreach(self::$Fields as $FieldID => $Field){
			$return[$FieldID] = $Field['name'];
		}
		return $return;
	}
    public static function getFieldName($fieldId){
		return self::$Fields[$fieldId]['name'];
	}
}
