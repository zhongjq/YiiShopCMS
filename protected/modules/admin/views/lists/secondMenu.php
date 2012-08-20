<?php
	$this->SecondMenu=array(
		array(  'label' => Yii::t('AdminModule.products',"Add list"),
				'url'   => array('/admin/products/lists/add'),
				'active'=> $this->getAction()->getId() == 'addlist' ),

	);