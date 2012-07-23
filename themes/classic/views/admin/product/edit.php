<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID,
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/productcreate.js",CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/jquery.form.js",CClientScript::POS_BEGIN);

$this->renderPartial('SecondMenu');

$this->renderPartial('_form', array('Product'=>$Product));

?>