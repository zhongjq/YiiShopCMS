<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$product->id,
);

$this->SecondMenu=array(
	array(  'label' => 'Поля',
			'url'   => $this->createUrl('/admin/product/fields',array('productId'=>$product->id)),
			'active'=> $this->getAction()->getId() == 'fields' ),
);

echo $form;

?>