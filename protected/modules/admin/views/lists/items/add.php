<?php

$this->breadcrumbs=array(
    Yii::t('lists',"Lists") => array("/admin/lists"),
    Yii::t('lists',"Items list #").$list->id => $this->createUrl('/admin/lists/items',array('id'=>$list->id) ),
    Yii::t('lists',"Add items")
);

$this->renderPartial('items/secondMenu',array('list'=>$list));

?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'additems-form',
	'enableAjaxValidation'=>true,
	'clientOptions' => array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>false,
		'validateOnType'=>false,
	)
)); ?>

	<div>
		<?php echo $form->labelEx($listItem,'name'); ?>
		<?php echo $form->textArea($listItem, 'name', array('class'=>'span4','maxlength' => 300, 'rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($listItem,'name'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($listItem->isNewRecord ? Yii::t('main',"Add") : Yii::t('main',"Save"), array("class"=>"btn")); ?>
	</div>

<?php $this->endWidget(); ?>

</div>