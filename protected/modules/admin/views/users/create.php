<?php
$this->breadcrumbs=array(
	'Пользователи'  =>  array('index'),
	'Добавление пользователя',
);

$this->SecondMenu=array(
	array('label'=>'Добавить', 'url'=>array('create'), 'active'=>$this->getId() =='users'),
	array('label'=>'List Users', 'url'=>array('index')),
);
?>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>