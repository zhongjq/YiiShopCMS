<?php

class TypeFields
{
	const INTEGER = 1;
	const STRING = 2;
	const PRICE = 3;
	const TEXT = 4;
    const LISTS = 5;
    
	public static $TypeToIDFields = array(
		'integer'   => self::INTEGER,
		'string'    => self::STRING,
		'price'     => self::PRICE,
		'text'      => self::TEXT,
        'list'      => self::LISTS,
	);

	public static $Fields = array(
		self::STRING    =>  array(
			'name'      =>  "Строка",
			'class'     =>  "StringFields",
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
			'class'     =>  "IntegerFields",
			'dbType'    =>  "int(11)",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  11,
			),
		),
		self::TEXT   =>  array(
			'name'      =>  "Текст",
			'type'      =>  "text",
			'class'     =>  "TextFields",
			'dbType'    =>  "text",
			'form'      =>  array(
				'type'  =>  'textarea',
			),
		),
		self::PRICE     => array(
			'name'      =>  "Цена",
			'type'      =>  "price",
			'class'     =>  "PriceFields",
			'dbType'    =>  "decimal(9,2)",
			'form'      =>  array(
				'type'      =>  'text',
				'maxlength' =>  12,
			),
		),
    	self::LISTS     => array(
			'name'      =>  "Список",
			'type'      =>  "list",
			'class'     =>  "ListFields",
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
