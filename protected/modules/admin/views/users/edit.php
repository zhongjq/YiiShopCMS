<?php
$this->breadcrumbs=array(
	'Пользователи'                  =>  array('/admin/users/index'),
	'Редактирование #'.$model->id 
);

$this->renderPartial('secondMenu');

echo $model->getModelCForm();

?>