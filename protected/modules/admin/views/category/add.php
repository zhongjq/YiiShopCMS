<?php
$this->pageTitle    =	Yii::t("categories", "Add category");
$this->breadcrumbs=array(
	Yii::t("categories", "Categories")	=>	array('/admin/category/index'),
	Yii::t("categories", "Add category")
);

$this->renderPartial('secondMenu');

echo $form;
?>