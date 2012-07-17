<?php

class TypeFields
{
	const NUMERIC = 1;
	const STRING = 2;
	const PRICE = 3;

	public static $Fields = array(
		self::NUMERIC   => array('name'=> "Число"),
		self::STRING    => array('name'=> "Строка"),
		self::PRICE     => array('name'=> "Цена"),
	);

	public static function getFieldsList(){
		$return = array();
		foreach(self::$Fields as $FieldID => $Field){
			$return[$FieldID] = $Field['name'];
		}
		return $return;
	}
}
