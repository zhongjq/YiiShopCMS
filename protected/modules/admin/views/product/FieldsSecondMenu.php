<?php
$this->SecondMenu=array(
	array(  'label' => 'Поля',
		'url'   => $this->createUrl('/admin/product/fields',array('ProductID'=>$Product->ID)),
		'active'=> $this->getAction()->getId() == 'fields' ),
	array(  'label' => 'Добавить поле',
		'url'   => $this->createUrl('/admin/product/addfield',array('ProductID'=>$Product->ID)),
		'active'=> $this->getAction()->getId() == 'addfield' ),
);