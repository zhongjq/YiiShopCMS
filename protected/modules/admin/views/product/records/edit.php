<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID." ({$Product->Name})" => $this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)),
	'Редактирование товара',
);

$this->renderPartial('records/SecondMenu',array('Product'=>$Product));


Yii::app()->getClientScript()->registerScript("select",'$(function(){$("form select").chosen();});');

echo $Form->render(); 

?>