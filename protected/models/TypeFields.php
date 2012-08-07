<?php

class TypeFields
{
	const INTEGER = 1;
	const STRING = 2;
	const PRICE = 3;

	public static $TypeToIDFields = array(
		'integer'=> self::INTEGER,
		'string'=> self::STRING,
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
		self::PRICE     => array(
			'name'=> "Цена"
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
