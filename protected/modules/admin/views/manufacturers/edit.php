<?php
$this->pageTitle    =	Yii::t("manufacturers", "Edit manufacturer");
$this->breadcrumbs=array(
	Yii::t("manufacturers", "Manufacturers")	=>	array('/admin/manufacturers'),
	Yii::t("manufacturers", "Edit manufacturer")
);

$this->renderPartial('SecondMenu');

//put fancybox on page
$this->widget('ext.fancybox.EFancyBox', array('target'=>'a#fancy-link','config'=>array()));

echo $Form;
?>