<?php
$this->breadcrumbs=array(
	'Cities'=>array('index'),
	'Manage',
);

$this->SecondMenu=array(
	array('label'=>'Добавить', 'url'=>array('add'),'active'=> $this->getAction()->getId() == 'add' ),
	array('label'=>'Производители', 'url'=>array('manufacturers'),'active'=> $this->getAction()->getId() == 'manufacturers' ),
);

?>