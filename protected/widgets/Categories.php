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

		$menu = $this->title ? array_merge(array(array('label'=>$this->title,'linkOptions'=>array('class'=>'header'))),
											Category::getMenuArray($category)) : Category::getMenuArray($category);

        $this->controller->renderPartial('//widgets/categories',array(
			"title" => $this->title,
			"categories" => $menu,
		));
	}
}