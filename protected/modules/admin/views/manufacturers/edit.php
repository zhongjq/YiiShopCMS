<?php
$this->pageTitle    =	Yii::t("manufacturers", "Edit manufacturer");
$this->breadcrumbs=array(
	Yii::t("manufacturers", "Manufacturers")	=>	array('/admin/manufacturers'),
	Yii::t("manufacturers", "Edit manufacturer")
);

$this->renderPartial('SecondMenu');

echo $Form;
?>