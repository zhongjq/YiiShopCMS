<?php

class Categories extends CWidget
{
    public $id;
    public $title;
	public function run(){
        if ( $this->id ) {
		    $category = Category::model()->findByPk($this->id);
            if ( $category ) $category = $category->descendants()->findAll();
        } else
            $category = Category::model()->findAll(array('order'=>'lft'));


        $this->controller->renderPartial('//widgets/categories',array(
			"title" => $this->title,
			"categories" => Category::getMenuArray($category),
		));
	}
}