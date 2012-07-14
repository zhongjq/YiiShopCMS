<?php
$this->pageTitle=Yii::app()->name . ' - Завершение регистрация';
?>

<div class="row">	
	<div class="span9 offset4">

		<h1>Завершение регистрация</h1>


<?php echo CHtml::beginForm(); ?>

	<div class="control-group<?= isset($form->errors['UserName']) ? ' error' : null ?>">
		<?php echo CHtml::activeLabelEx($form,'UserName'); ?>
		<div class="controls">
			<?php echo CHtml::activeTextField($form,'UserName',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo CHtml::error($form,'UserName',array('class'=>'help-inline')); ?>
		</div>
	</div>		
	
	<div class="control-group<?= isset($form->errors['Email']) ? ' error' : null ?>">
		<?php echo CHtml::activeLabelEx($form,'Email'); ?>
		<div class="controls">
			<?php echo CHtml::activeTextField($form,'Email',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo CHtml::error($form,'Email',array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<br>
    <div>
        <?php echo CHtml::submitButton('Зарегистрироваться', array("class"=>"btn")); ?>
    </div>

<?php echo CHtml::endForm(); ?>
</div>
</div><!-- form -->	