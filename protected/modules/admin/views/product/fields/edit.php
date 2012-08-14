<?php
$this->pageTitle = Yii::t('AdminModule.products',"Add field product.");

$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID => $this->createUrl('/admin/product/edit',array('ProductID'=>$Product->ID)),
	'Поля товара' => $this->createUrl('/admin/product/fields',array('ProductID'=>$Product->ID)),
	'Редактирование поле',
);

$this->renderPartial('fields/SecondMenu',array('Product'=>$Product));

echo $Form;
?>