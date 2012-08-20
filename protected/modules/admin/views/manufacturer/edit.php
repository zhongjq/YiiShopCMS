<?php
$this->pageTitle    =	Yii::t("manufacturers", "Edit manufacturer");
$this->breadcrumbs=array(
	Yii::t("manufacturers", "Manufacturers")	=>	array('/admin/manufacturer/index'),
	Yii::t("manufacturers", "Edit manufacturer")
);

$this->renderPartial('secondMenu');

//put fancybox on page
#$this->widget('ext.fancybox.EFancyBox', array('target'=>'a#fancy-link','config'=>array()));

echo $form;
?>