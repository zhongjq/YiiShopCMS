<?php
	$this->SecondMenu=array(
		array(  'label' => Yii::t('AdminModule.products',"Add list"),
				'url'   => array('products/addlist'),
				'active'=> $this->getAction()->getId() == 'add' ),

	);