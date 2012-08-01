<?php $this->pageTitle=Yii::app()->name . ' - Вход'; ?>

<div class="row">
	
	<div class="span9 offset3">
		<h1>Вход</h1>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<div><?php echo CHtml::hiddenField('referer_url111', CHttpRequest::getUrlReferrer());  ?></div>

		<div><?php echo $form->errorSummary($model); ?></div>

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
				<?php echo $form->passwordField($model,'Password',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'Password',array('class'=>'help-inline')); ?>
			</div>
		</div>
		
		<div>
			<?php echo CHtml::submitButton('Воити', array("class"=>"btn") ); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div>

</div><!-- form -->
