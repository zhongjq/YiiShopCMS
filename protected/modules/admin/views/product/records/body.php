<?php

$this->renderPartial('records/secondMenu',array('product'=>$product));

Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/chosen/chosen.css');
Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/chosen/chosen.jquery.min.js');

Yii::app()->getClientScript()->registerScript("select",'$(function(){$("form select").chosen({allow_single_deselect:true});});');

echo $form->render();

?>