<?php
$this->pageTitle = Yii::t('products',"Add field product.");

$this->breadcrumbs=array(
	Yii::t("products","Constructor Goods") => array('index'),
	Yii::t('products',"Fields product") => $this->createUrl('/admin/constructor/fields',array('id'=>$product->id)),
	Yii::t('products',"Add field"),
);

$this->renderPartial('fields/secondMenu',array('product'=>$product));

echo $form->render();
?>