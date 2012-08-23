<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$product->id." ({$product->name})" => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	'Добавление товара',
);

$this->renderPartial('records/secondMenu',array('product'=>$product));

Yii::app()->getClientScript()->registerScript("select",'$(function(){$("form select").chosen({allow_single_deselect:true});});');


echo $form->render(); 

?>