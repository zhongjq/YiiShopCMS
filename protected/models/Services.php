<?php

class Services
{
	const LOCAL_USER    = 1;
	const GOOGLE        = 2;
	const YANDEX        = 3;

	static $Services = array(
		self::LOCAL_USER    => array('name'=> 'Локальный пользователь' ),
		self::GOOGLE        => array('name'=> 'Google' ),
		self::YANDEX        => array('name'=> 'Yandex' ),
	);

	public static function getServiceString($IdService){
		return self::$Services[$IdService]['name'];
	}

	public static function getServiceId($Name){
		foreach(self::$Services as $IdService => $Service ){
			if ( strtolower($Name) == strtolower($Service['name']) )
				return $IdService;
		}

		return false;
	}

	public static function getServicesList(){
		$return = array();

		foreach(self::$Services as $IdService => $Service ){
			$return[$IdService] = $Service['name'];
		}

		return $return;
	}

}
