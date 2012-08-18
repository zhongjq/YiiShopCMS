<?php

class Languages {
	const RU = 1;
	const EN = 2;

	public static $Languages = array(
		self::RU    =>  array(
			'name'	=> 'Русский',
			'value'	=> 'ru',
		),
		self::EN    =>  array(
			'name'	=> 'English',
			'value'	=> 'en',
		),
	);

	public static function getLanguagesList(){
		$return = array();
		foreach(self::$Languages as $LanguageID => $Language){
			$return[$LanguageID] = $Language['name'];
		}
		return $return;
	}
}

?>