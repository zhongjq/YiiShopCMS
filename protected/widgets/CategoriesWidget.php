<?php

class CategoriesWidget extends CWidget
{
<<<<<<< HEAD
 	public function run(){
		$Category = Categories::model()->findByPk($this->ID)->descendants()->findAll();
		
        $this->controller->renderPartial('//widgets/categories',array(
			"title" =>  $Category->Name,
			"Categories" =>  Categories::getMenuItems($Category),
=======
	public function run(){
		$Category = Categories::model()->findByPk($this->ID);
		$this->render('widget',array(
			"title" =>  $Category->Name,
			"Category" =>  $Category,
>>>>>>> 27832d0ce2b48c85a2c02eae7411f7c2ac3ec39f
		));
	}
}
