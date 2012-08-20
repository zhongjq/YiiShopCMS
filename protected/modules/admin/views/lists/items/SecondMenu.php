<?php
    $this->SecondMenu=array(
    	array(  'label' => Yii::t('AdminModule.products',"Add items"),
				'url'   => $this->createUrl('/admin/product/additems',array('ListID'=>$List->ID)),
				'active'=> $this->getAction()->getId() == 'additems' ),

	);