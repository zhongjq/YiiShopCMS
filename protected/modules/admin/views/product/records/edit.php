<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$product->id." ({$product->name})" => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	'Редактирование товара',
);

$this->renderPartial('records/body',array('product'=>$product,'form'=>$form));

?>