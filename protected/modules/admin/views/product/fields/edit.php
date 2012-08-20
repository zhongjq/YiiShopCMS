<?php
$this->pageTitle = Yii::t('fields',"Edit field product");

$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$product->id => $this->createUrl('/admin/product/edit',array('id'=>$product->id)),
	'Поля товара' => $this->createUrl('/admin/product/fields',array('id'=>$product->id)),
	Yii::t('fields',"Edit field product"),
);

$this->renderPartial('fields/secondMenu',array('product'=>$product));

echo $form;
?>