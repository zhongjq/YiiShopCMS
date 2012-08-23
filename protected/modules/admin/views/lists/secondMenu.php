<?php
	$this->SecondMenu=array(
		array(  'label' => Yii::t('lists',"Add list"),
				'url'   => array('/admin/lists/add'),
				'active'=> $this->getAction()->getId() == 'add' ),

	);