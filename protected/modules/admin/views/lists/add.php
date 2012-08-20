<?php
$this->pageTitle = Yii::t('lists',"Add list");

$this->breadcrumbs=array(
	Yii::t('lists',"Lists") => array("/admin/lists"),
    Yii::t('lists',"Add list")
);

$this->renderPartial('secondMenu');

echo $form;
?>