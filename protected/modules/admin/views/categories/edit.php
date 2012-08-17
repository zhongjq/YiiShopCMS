<?php
$this->pageTitle    =	Yii::t("categories", "Edit category");
$this->breadcrumbs=array(
	Yii::t("categories", "Categories")	=>	array('/admin/categories'),
	Yii::t("categories", "Edit category")
);

$this->renderPartial('SecondMenu');

echo $Form;
?>