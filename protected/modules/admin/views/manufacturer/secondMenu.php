<?php
$this->SecondMenu=array(
	array(	'label'	=>	Yii::t("manufacturers", "Add manufacturer"),
			'url'	=>	array('add'),
			'active'=>	$this->getAction()->getId() =='add'
		),
);