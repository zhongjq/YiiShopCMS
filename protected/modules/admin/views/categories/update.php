<?php
$this->breadcrumbs=array(
	'Категории'=>array('index'),
	'Редактирование категории #' .$model->ID ,
);

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>