<?php
$this->SecondMenu=array(
	array(  'label' => Yii::t('product','Add'),
			'url'   => array('/admin/product/add','id'=> $product->id) ),
            
    array(  'label' => Yii::t('product','Export'),
			'url'   => array('/admin/product/export','id'=> $product->id) ),            
    
    array(  'label' => Yii::t('product','Import'),
    		'url'   => array('/admin/product/import','id'=> $product->id) ),            
);
