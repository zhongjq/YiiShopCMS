<?php

class ManufacturersWidget extends CWidget
{
	public $title;

	public function run(){
		$manufacturers = Manufacturers::model()->findAll(array('order'=>'Name'));

		$this->controller->renderPartial('//widgets/manufacturers',array(
			"title" =>  $this->title,
			"manufacturers" =>  Manufacturers::getMenuArray($manufacturers),
		));
	}
}
