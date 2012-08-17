<?php
$this->pageTitle    =	Yii::t("categories", "Add category");
$this->breadcrumbs=array(
	Yii::t("categories", "Categories")	=>	array('/admin/categories'),
	Yii::t("categories", "Add category")
);

$this->renderPartial('SecondMenu');

echo $Form;
?>