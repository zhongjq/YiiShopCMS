<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	$model->Name,
);


?>

<h1>View Categories #<?php echo $model->ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'lft',
		'rgt',
		'Level',
		'Status',
		'Alias',
		'Name',
		'Вescription',
	),
)); ?>
