<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID,
);

$this->SecondMenu=array(
	array(  'label' => 'Поля',
			'url'   => $this->createUrl('/admin/product/fields',array('id'=>$Product->ID)),
			'active'=> $this->getAction()->getId() == 'fields' ),
);

echo $Form;

?>