<?php

class Cart extends CModel {

	public static $instance = null;

	public static function model(){
		if (self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function attributeNames() {
		return array();
	}

	public function quantity(){
		return 1;
	}

}

?>
