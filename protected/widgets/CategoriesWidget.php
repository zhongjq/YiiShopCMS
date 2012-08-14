<?php

class CategoriesWidget extends CWidget
{
 	public function run(){
		$Category = Categories::model()->findByPk($this->ID)->descendants()->findAll();
		
        $this->controller->renderPartial('//widgets/categories',array(
			"title" =>  $Category->Name,
			"Categories" =>  Categories::getMenuItems($Category),
		));
	}
}
