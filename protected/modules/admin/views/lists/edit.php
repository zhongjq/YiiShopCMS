<?php

$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
	Yii::t('AdminModule.products',"Lists") => array("/admin/products/lists"),
    "Добавление списка"
);

$this->renderPartial('lists/SecondMenu');

echo $Form;
?>