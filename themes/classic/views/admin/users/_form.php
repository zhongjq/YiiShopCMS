<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->labelEx($model,'Status'); ?>
		<?php echo $form->dropDownList($model,'Status', Statuses::getStatusesList() ); ?>
		<?php echo $form->error($model,'Status'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'RoleID'); ?>
		<?php echo $form->dropDownList($model,'RoleID', Roles::getRolesList() ); ?>
		<?php echo $form->error($model,'RoleID'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'RegistrationDateTime'); ?>
		<?php echo $form->textField($model,'RegistrationDateTime'); ?>
		<?php echo $form->error($model,'RegistrationDateTime'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'ServiceID'); ?>
		<?php echo $form->dropDownList($model,'ServiceID', Services::getServicesList() ); ?>
		<?php echo $form->error($model,'ServiceID'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'ServiceUserID'); ?>
		<?php echo $form->textField($model,'ServiceUserID',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ServiceUserID'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'Email'); ?>
		<?php echo $form->textField($model,'Email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'Email'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->