<?php
$this->breadcrumbs=array(
	'Пользователи'  =>  array('/admin/users/index'),
	'Добавление пользователя',
);

$this->renderPartial('secondMenu');

echo $model->getModelCForm();
?>