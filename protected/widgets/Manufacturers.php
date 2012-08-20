<?php

class Manufacturers extends CWidget
{
	public $title;

	public function run(){
		$manufacturers = Manufacturer::model()->findAll(array('order'=>'name'));

		$this->controller->renderPartial('//widgets/manufacturers',array(
			"title" =>  $this->title,
			"manufacturers" =>  Manufacturer::getMenuArray($manufacturers),
		));
	}
}
