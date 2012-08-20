<?php
$this->pageTitle    =	Yii::t("manufacturers", "Add manufacturer");
$this->breadcrumbs=array(
	Yii::t("manufacturers", "Manufacturers")	=>	array('/admin/manufacturer/index'),
	Yii::t("manufacturers", "Add manufacturer")
);

$this->renderPartial('secondMenu');

echo $form;
?>