<?php

class Roles
{
	const USER = 1;
	const ADMINISTRATOR = 2;

	static $Roles = array(
		self::USER => array('name'=> 'User' ),
		self::ADMINISTRATOR => array('name'=> 'Administrator' ),
	);


	public static function getRoleString($IdRole){
		return self::$Roles[$IdRole]['name'];
	}

	public static function getRolesList(){
		$return = array();

		foreach(self::$Roles as $IDRole => $Role){
			$return[$IDRole] = $Role['name'];
		}

		return $return;
	}

}
