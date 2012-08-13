<?php
$this->SecondMenu=array(
	array(  'label' => 'Добавить товар',
			'url'   => $this->createUrl('/admin/product/add',array('ProductID'=> $Product->ID)),
			'active'=> $this->getAction()->getId() == 'add' ),
);