<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ID'); ?>
		<?php echo $form->textField($model,'ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Status'); ?>
		<?php echo $form->textField($model,'Status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RoleID'); ?>
		<?php echo $form->textField($model,'RoleID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RegistrationDateTime'); ?>
		<?php echo $form->textField($model,'RegistrationDateTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ServiceID'); ?>
		<?php echo $form->textField($model,'ServiceID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ServiceUserID'); ?>
		<?php echo $form->textField($model,'ServiceUserID',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Email'); ?>
		<?php echo $form->textField($model,'Email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Password'); ?>
		<?php echo $form->passwordField($model,'Password',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->