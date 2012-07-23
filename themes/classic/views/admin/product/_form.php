<div class="row">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'users-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<div class="span4">
		<div class="form well">

			<p class="note">Fields with <span class="required">*</span> are required.</p>

			<?php echo $form->errorSummary($Product); ?>

			<div>
				<?php echo $form->checkBox($Product,'Status'); ?>
				<?php echo $form->labelEx($Product,'Status'); ?>
				<?php echo $form->error($Product,'Status'); ?>
			</div>

			<div>
				<?php echo $form->labelEx($Product,'Name'); ?>
				<?php echo $form->textField($Product,'Name',array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($Product,'Name'); ?>
			</div>

			<div>
				<?php echo $form->labelEx($Product,'Alias'); ?>
				<?php echo $form->textField($Product,'Alias',array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($Product,'Alias'); ?>
			</div>

			<div class="buttons">
				<?php echo CHtml::submitButton($Product->isNewRecord ? 'Create' : 'Save',array("class"=>"btn")); ?>
			</div>


		</div>
	</div>

	<div class="span8">
		<table id="ProductField" class="table table-bordered table-striped">
			<thead>
			<tr>
				<td width="20">ID</td>
				<td class="span2">Тип поля</td>
				<td>Наименование</td>
				<td width="100">Обязательно</td>
				<td width="100">Фильтр</td>
				<td width="10"></td>
				<td width="10"></td>
			</tr>
			</thead>
			<tbody>
				<? if ( $Product->productsFields() ) : ?>
					<? foreach($Product->productsFields() as $Field) : ?>
						<? $this->renderPartial('_productField', array('Field'=>$Field)); ?>
					<? endforeach ?>
				<? endif ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">
						<div class="buttons">
							<?php echo CHtml::Button('Добавить поле',array("class"=>"btn","id"=>"AddField")); ?>
							<?php echo CHtml::Button('Отмена',array("class"=>"btn hide","id"=>"CancelAddField")); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php $this->endWidget(); ?>

</div>



<form class="modal hide form-horizontal" id="FieldSetting">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Добавление поля</h3>
	</div>
	<div class="modal-body">

		<div class="control-group">
			<?= CHtml::label("Тип поля",'ProductField[FieldType]',array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo CHtml::dropDownList("ProductField[FieldType]",'', TypeFields::getFieldsList(), array("class"=>"span2") ); ?>
				<span class="help-block"></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo CHtml::label("Наименование поля",'ProductField[Name]',array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo CHtml::textField("ProductField[Name]",'',array('size'=>60,'maxlength'=>255)); ?>
				<span class="help-block"></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo CHtml::label("Англииское наименование имя поля",'ProductField[Alias]',array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo CHtml::textField("ProductField[Alias]",'',array('size'=>60,'maxlength'=>255)); ?>
				<span class="help-block"></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo CHtml::label("Обязательно для заполения",'ProductField[IsMandatory]',array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo CHtml::checkBox("ProductField[IsMandatory]",false); ?>
				<span class="help-block"></span>
			</div>
		</div>

		<div class="control-group">
			<?php echo CHtml::label("Использовать для фильтра",'ProductField[IsFilter]',array('class'=>'control-label')); ?>
			<div class="controls">
				<?php echo CHtml::checkBox("ProductField[IsFilter]",false); ?>
				<span class="help-block"></span>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<?php echo CHtml::Button('Отмена',array("data-dismiss"=>"modal","class"=>"btn")); ?>
		<?php echo CHtml::submitButton('Добавить',array("id"=>"AddField","class"=>"btn btn-primary")); ?>
	</div>
</form>