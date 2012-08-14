<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'categories-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div>
		<?php echo $form->labelEx($model,'Parent'); ?>
		<?php echo $form->dropDownList( $model,'Parent',
										CHtml::listData(Categories::model()->findAll(array(
																'order'=>'lft',
																'condition'=>'ID != :ID',
																'params'=>array(':ID'=> $model->ID ? $model->ID : 0 )
															)
															), 'ID', 'Name'),
										array('empty' => "") ); ?>
		<?php echo $form->error($model,'Parent'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'Status'); ?>
		<?php echo $form->checkBox($model,'Status'); ?>
		<?php echo $form->error($model,'Status'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'Name'); ?>
		<?php echo $form->textField($model,'Name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'Name'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'Description'); ?>
		<?php echo $form->textArea($model,'Description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Description'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->