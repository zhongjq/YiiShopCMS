<?php
$this->breadcrumbs=array(
	'Пользователи'                  =>  array('index'),
	'Пользователь #'.$model->ID     =>  array('view','id'=>$model->ID),
	'Редактирование пароля',
);

$this->SecondMenu=array(
	array('label'=>'Добавить', 'url'=>array('create'))
);
?>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->labelEx($model,'Password'); ?>
		<?php echo $form->textField($model,'Password',array('value'=>'')); ?>
		<?php echo $form->error($model,'Password'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'PasswordRepeat'); ?>
		<?php echo $form->textField($model,'PasswordRepeat'); ?>
		<?php echo $form->error($model,'PasswordRepeat'); ?>
	</div>

	<div class="control-group<?= isset($model->errors['VerifyCode']) ? ' error' : null ?>">
		<?if(extension_loaded('gd')):?>
		<?=$form->labelEx($model, 'VerifyCode')?>
		<div>

			<?$this->widget('CCaptcha')?>

		</div>
		<div class="controls">
			<?=$form->textField($model, 'VerifyCode')?>
			<?php echo $form->error($model,'VerifyCode',array('class'=>'help-inline')); ?>
		</div>
		<?endif?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn")); ?>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->