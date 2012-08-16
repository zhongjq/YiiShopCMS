<?php
$this->SecondMenu=array(
	array(	'label'	=>	Yii::t("AdminModule.categories", "Add category"),
			'url'	=>	array('add'),
			'active'=>	$this->getAction()->getId() =='add'
		),
);