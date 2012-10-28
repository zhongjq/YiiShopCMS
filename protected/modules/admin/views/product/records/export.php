<?php
$this->pageTitle = Yii::t('product','Export')." ".$product->name;


$this->breadcrumbs=array(
	$product->name => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	Yii::t('product','Export'),
);

$this->renderPartial('records/secondMenu',array('product'=>$product));


echo $form;

?>