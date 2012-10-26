<?php
$this->pageTitle = Yii::t('product','Export')." ".$product->name;


$this->breadcrumbs=array(
	$product->name => $this->createUrl('/admin/product/view',array('id'=>$product->id)),
	Yii::t('product','Export'),
);

$this->renderPartial('records/secondMenu',array('product'=>$product));


echo $form;


$this->widget('ext.EExcelView.EExcelView', array(
     'dataProvider'=> $model->search(),
'grid_mode'=>'export',
            'title'=>'Title',
            'filename'=>'report.xlsx',
            'stream'=>true,
            'exportType'=>'Excel2007',  
)); 

?>