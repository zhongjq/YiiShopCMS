<?php
$this->pageTitle    =	Yii::t("categories", "Edit category");
$this->breadcrumbs=array(
	Yii::t("categories", "Categories")	=>	array('/admin/category'),
	Yii::t("categories", "Edit category")
);

$this->renderPartial('secondMenu');

echo $form;
?>