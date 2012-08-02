<?php
$this->SecondMenu=array(
	array(  'label' => 'Поля',
		'url'   => $this->createUrl('/admin/product/fields',array('id'=>$Product->ID)),
		'active'=> $this->getAction()->getId() == 'fields' ),
	array(  'label' => 'Добавить поле',
		'url'   => $this->createUrl('/admin/product/addfield',array('id'=>$Product->ID)),
		'active'=> $this->getAction()->getId() == 'addfield' ),
);