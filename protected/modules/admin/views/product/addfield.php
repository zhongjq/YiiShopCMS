<?php
$this->pageTitle = Yii::t('AdminModule.products',"Add field product.");

$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID => $this->createUrl('/admin/product/edit',array('id'=>$Product->ID)),
	'Поля товара' => $this->createUrl('/admin/product/fields',array('id'=>$Product->ID)),
	'Добавить поле',
);

$this->renderPartial('FieldsSecondMenu',array('Product'=>$Product));

echo $Form;
?>