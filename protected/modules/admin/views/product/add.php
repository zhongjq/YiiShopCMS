<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID => $this->createUrl('/admin/product/view',array('id'=>$Product->ID)),
	'Добавление товара',
);

$this->renderPartial('GoodsSecondMenu',array('Product'=>$Product));

echo $Form->render();
?>