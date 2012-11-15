<?php

class Manufacturers extends CWidget
{
    public $id;    
	public $title;
	public function run(){
        
        if ( $this->id ) {
    	    $manufacturers = Manufacturer::model()->findByPk($this->id);
            if ( $manufacturers ) $manufacturers = $manufacturers->descendants()->findAll();
        } else
            $manufacturers = Manufacturer::model()->findAll(array('order'=>'name'));

        $menu = $this->title ? array(array('label'=>$this->title,'linkOptions'=>array('class'=>'header'))) + Manufacturer::getMenuArray($manufacturers) : Manufacturer::getMenuArray($manufacturers);


		$this->controller->renderPartial('//widgets/manufacturers',array(
			"title" => $this->title,
			"manufacturers" => $menu,
		));
	}
}
