<?php
$this->pageTitle    =	Yii::t("manufacturers", "Add manufacturer");
$this->breadcrumbs=array(
	Yii::t("manufacturers", "Manufacturers")	=>	array('/admin/manufacturers'),
	Yii::t("manufacturers", "Add manufacturer")
);

$this->renderPartial('SecondMenu');

echo $Form;
?>