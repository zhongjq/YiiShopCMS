<?php
$this->SecondMenu=array(
	array(  'label' => Yii::t('products',"Fields"),
			'url'   => $this->createUrl('/admin/constructor/fields',array('id'=>$product->id)),
			'active'=> $this->getAction()->getId() == 'fields' ),
	array(  'label' => Yii::t('products',"Add field"),
			'url'   => $this->createUrl('/admin/constructor/addfield',array('id'=>$product->id)),
			'active'=> $this->getAction()->getId() == 'addfield' ),
);