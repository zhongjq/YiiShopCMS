<?php

class CategoriesWidget extends CWidget
{
	public function run(){
		$Category = Categories::model()->findByPk($this->ID);
		$this->render('widget',array(
			"title" =>  $Category->Name,
			"Category" =>  $Category,
		));
	}
}
