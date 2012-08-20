<?php

class TypeField
{
	const INTEGER = 1;
	const STRING = 2;
	const PRICE = 3;
	const TEXT = 4;
    const LISTS = 5;
    const CATEGORIES = 6;

	public static $TypeToIDFields = array(
		'integer'   => self::INTEGER,
		'string'    => self::STRING,
		'price'     => self::PRICE,
		'text'      => self::TEXT,
        'list'      => self::LISTS,
        'categories'=> self::CATEGORIES,
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
    	self::CATEGORIES     => array(
			'name'      =>  "Категория(и)",
			'type'      =>  "categories",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
                'type'  =>  'dropdownlist',
				'empty' =>  '',
            ),
		),
	);

	public static function getFieldsList(){
		$return = array();
		foreach(self::$Fields as $FieldID => $Field){
			$return[$FieldID] = $Field['name'];
		}
		return $return;
	}
}
