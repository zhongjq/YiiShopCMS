<?php
$this->pageTitle = Yii::t('products',"Add field product.");

$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$product->id => $this->createUrl('/admin/product/edit',array('id'=>$product->id)),
	'Поля товара' => $this->createUrl('/admin/product/fields',array('id'=>$product->id)),
	'Добавить поле',
);

$this->renderPartial('fields/secondMenu',array('product'=>$product));

echo $form->render();
?>