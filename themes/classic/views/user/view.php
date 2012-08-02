<?php
$this->breadcrumbs=array(
	//'Пользователи'=>array('index'),
	$model->UserName ? $model->UserName : $model->Email,
);
?>

<h2>Ваш профиль</h2>

<?php $this->widget('zii.widgets.CDetailView', array(
		'data'		=> $model,
		'attributes'=> array(
			//'id',
			//'role',
			'UserName',
			//'password',
			'Email',
			array(
				'type'	=> 'raw',
				'label' => CHtml::link('Редактировать профиль',$this->createUrl('/user/edit',array('id'=>Yii::app()->user->id))),
				'value'	=> ''
			)
		),
		'cssFile'		=> false,
		'htmlOptions'	=> array('class'=>'table table-striped table-bordered table-condensed')
)); ?>
