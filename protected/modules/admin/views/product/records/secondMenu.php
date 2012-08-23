<?php
$this->SecondMenu=array(
	array(  'label' => 'Добавить товар',
			'url'   => $this->createUrl('/admin/product/add',array('id'=> $product->id)),
			'active'=> $this->getAction()->getId() == 'add' ),
);