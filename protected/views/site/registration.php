<?php
$this->pageTitle=Yii::app()->name . ' - Регистрация';
?>

<div class="form">

	<h1>Регистрация</h1>

	<div>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<p class="note">Поля отмеченные <span class="required">*</span> обязательны для заполнения.</p>

		<div class="control-group<?= isset($model->errors['Email']) ? ' error' : null ?>">
			<?php echo $form->labelEx($model,'Email'); ?>
			<div class="controls">
				<?php echo $form->textField($model,'Email',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'Email',array('class'=>'help-inline')); ?>
			</div>
		</div>	

		<div class="control-group<?= isset($model->errors['Password']) ? ' error' : null ?>">
			<?php echo $form->labelEx($model,'Password'); ?>
			<div class="controls">
				<?php echo $form->PasswordField($model,'Password',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'Password',array('class'=>'help-inline')); ?>
			</div>
		</div>

		<div class="control-group<?= isset($model->errors['PasswordRepeat']) ? ' error' : null ?>">
			<?php echo $form->labelEx($model,'PasswordRepeat'); ?>
			<div class="controls">
				<?php echo $form->PasswordField($model,'PasswordRepeat',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'PasswordRepeat',array('class'=>'help-inline')); ?>
			</div>
		</div>

		<div class="control-group<?= isset($model->errors['VerifyCode']) ? ' error' : null ?>">
		<?if(extension_loaded('gd') && Yii::app()->user->isGuest):?>
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
		
		
		<div>
			<?php echo CHtml::submitButton('Зарегистрироваться',array('class'=>'btn')); ?>
		</div>
		
		<div class="control-group">		
Нажимая на кнопку "Зарегистрироваться", вы соглашаетесь с условиями
<?=CHtml::link('Пользовательского соглашения',$this->createUrl('/page/viewpage',array('page'=>'terms')))?> и 
<?=CHtml::link('Политикой конфиденциальности',$this->createUrl('/page/viewpage',array('page'=>'privacy')))?>.
		</div>		
		
	<?php $this->endWidget(); ?>

	</div><!-- form -->


</div>