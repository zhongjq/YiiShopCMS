<?php
$this->pageTitle    =	Yii::t("categories", "Edit category");
$this->breadcrumbs=array(
	Yii::t("categories", "Categories")	=>	array('/admin/category/index'),
	Yii::t("categories", "Edit category")
);

$this->renderPartial('secondMenu');

echo $form;
?>