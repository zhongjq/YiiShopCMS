<?php

$this->renderPartial('records/secondMenu',array('product'=>$product));

Yii::app()->clientScript->registerPackage('chosen');

Yii::app()->getClientScript()->registerScript("select",'$(function(){$("select.chzn-select").chosen({allow_single_deselect:true});});');

echo $form->render();

?>