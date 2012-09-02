<?php
$this->breadcrumbs=array(
	$product->name => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	'Редактирование товара',
);

$this->renderPartial('records/body',array('product'=>$product,'form'=>$form));

?>