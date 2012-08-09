<?php

$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
    Yii::t('AdminModule.products',"Lists") => array("/admin/products/lists"),
    Yii::t('AdminModule.products',"Items list").$List->ID => $this->createUrl('/admin/product/itemslist',array('ListID'=>$List->ID) ),
    Yii::t('AdminModule.products',"Add items")
);

$this->renderPartial('lists/SecondMenuItems',array('List'=>$List));

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'additems-form',
	'enableAjaxValidation'=>true,
    'clientOptions' => array(
      'validateOnSubmit'=>true,
      'validateOnChange'=>false,
      'validateOnType'=>false,
    ),
    
    
)); ?>

	<div>
		<?php echo $form->labelEx($ItemsList,'Name'); ?>
		<?php echo $form->textArea($ItemsList, 'Name', array('class'=>'span4','maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($ItemsList,'Name'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($ItemsList->isNewRecord ? 'Add' : 'Save',array("class"=>"btn")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->