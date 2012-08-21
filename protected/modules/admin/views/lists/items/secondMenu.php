<?php
    $this->SecondMenu=array(
    	array(  'label' => Yii::t('lists',"Add items"),
				'url'   => $this->createUrl('/admin/lists/additems',array('id'=>$list->id)),
				'active'=> $this->getAction()->getId() == 'additems' ),
	);