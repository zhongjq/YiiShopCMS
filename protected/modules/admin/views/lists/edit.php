<?php

$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
	Yii::t('lists',"Lists") => array("/admin/lists"),
    "Добавление списка"
);

$this->renderPartial('secondMenu');

echo $form;
?>