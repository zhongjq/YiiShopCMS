<?php
$this->breadcrumbs=array(
	'Категории'=>array('index'),
	'Добавление категории',
);

$this->SecondMenu=array(
	array('label'=>'Добавить категорию', 'url'=>array('add'), 'active'=>$this->getId() =='add'),
);
?>

<h1>Create Categories</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>