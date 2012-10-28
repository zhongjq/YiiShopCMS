<?php
$this->breadcrumbs=array(
    $product->name => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	Yii::t('product','Import'),
);

$this->renderPartial('records/secondMenu',array('product'=>$product));

echo $form;

?>