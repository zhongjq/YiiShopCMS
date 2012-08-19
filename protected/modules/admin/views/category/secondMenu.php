<?php
$this->SecondMenu=array(
	array(	'label'	=>	Yii::t("categories", "Add category"),
			'url'	=>	array('add'),
			'active'=>	$this->getAction()->getId() =='add'
		),
);