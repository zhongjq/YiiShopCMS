<?php

class Statuses
{
	const ACTIVE    = 0;
	const OFF       = 1;
	const CONFIRM_EMAIL = 2;

	static $Statuses = array(
		self::ACTIVE    => array('name'=> 'Активрованный' ),
		self::OFF       => array('name'=> 'Выключен' ),
		self::CONFIRM_EMAIL => array('name'=> 'Подтверждение email' ),
	);

	public static function getStatusString($IdStatus){
		return self::$Statuses[$IdStatus]['name'];
	}

	public static function getStatusesList(){
		$return = array();

		foreach(self::$Statuses as $IdStatus => $Status ){
			$return[$IdStatus] = $Status['name'];
		}

		return $return;
	}

}
