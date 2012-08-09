<?php

$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
    Yii::t('AdminModule.products',"Lists") => array("/admin/products/lists"),
    Yii::t('AdminModule.products',"Items list").$List->ID => $this->createUrl('/admin/product/itemslist',array('ListID'=>$Item->ListID) ),
    Yii::t('AdminModule.products',"Edit item #").$Item->ID
);

$this->renderPartial('lists/SecondMenu');

echo $Form;
?>