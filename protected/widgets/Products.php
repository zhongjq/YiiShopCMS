<?php

class Products extends CWidget
{
	public $title;

	public function run(){
		$products = Product::model()->findAll(array('order'=>'name'));
        
        $r = array();
        foreach ($products as $product) {
            $r[] = array(
                'label'       => $product->name,
                'url'         => array('/product/index', 'alias' => $product->alias),
                //'linkOptions' => array('class' => 'cat_link'),
                //'template' => '{menu} <span>['. $val->catCount .']</span>',
            );
        } 

		$this->controller->renderPartial('//widgets/products',array(
			"title" => $this->title,
			"products" => $r,
		));
	}
}
