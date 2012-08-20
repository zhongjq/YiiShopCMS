<?php

class Categories extends CWidget
{   
    public $id;
    public $title;    
	public function run(){
        if ( $this->id ) {
		    $categories = Category::model()->findByPk($this->id);
            if ( $categories ) $categories = $categories->descendants()->findAll();
        } else
            $categories = Category::model()->findAll(array('order'=>'lft'));        
        
		
        $this->controller->renderPartial('//widgets/categories',array(
			"title" =>  $this->title,
			"categories" =>  Category::getMenuArray($categories),
		));
	}
}