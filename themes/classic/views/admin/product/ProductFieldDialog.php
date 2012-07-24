<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog',
	'options'=>array(
		'title'     =>  Yii::t('AdminModule.main','Добавить поле'),
		'autoOpen'  =>  true,
		'modal'     =>  'true',
		'width'     =>  'auto',
		'height'    =>  'auto',
	),
)); ?>

<?php echo $form; ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>