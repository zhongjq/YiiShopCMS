<?php
	$this->SecondMenu=array(
		array('label'=>'Создать товар', 'url'=> array('create'), 'active'=> $this->getAction()->getId() == 'create' ),
		array(  'label' => Yii::t('AdminModule.products',"Lists"),
				'url'   => array('/admin/product/lists'),
				'active'=> $this->getAction()->getId() == 'create' ),
	);